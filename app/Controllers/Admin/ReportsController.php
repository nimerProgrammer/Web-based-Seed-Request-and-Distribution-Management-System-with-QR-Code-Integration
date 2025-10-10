<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\InventoryModel;
use App\Models\SeedRequestsModel;
use App\Models\BeneficiariesModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Dompdf\Dompdf;
use Dompdf\Options;
use \DateTime;
use \DateTimeZone;

class ReportsController extends BaseController
{

    /**
     * Sets the selected barangay for seed requests report in the session.
     *
     * This method processes the selected barangay data from the request,
     * and stores it in the session for later use in generating reports.
     *
     * @return ResponseInterface Redirects to the seed requests reports page after setting the barangay.
     */
    public function setReportBarangayView()
    {
        $barangay = $this->request->getPost( 'barangay_data' );

        if ( $barangay ) {
            session()->set( 'selected_report_barangay_name', $barangay );
        }

        return redirect()->to( base_url( '/admin/reports' ) );
    }

    /**
     * Displays the reports page.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function setListView( $type )
    {
        session()->set( 'selected_list', $type );
        return redirect()->to( '/admin/reports' ); // Adjust path as needed
    }
    /**
     * Sets the cropping season view based on user selection.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function setSeasonView()
    {
        $raw = $this->request->getPost( 'season_data' ); // e.g., "3|Dry|2025"

        if ( $raw && strpos( $raw, '|' ) !== false ) {
            list( $id, $season, $year ) = explode( '|', $raw, 3 );

            session()->set( [
                'selected_cropping_season_id'   => $id,
                'selected_cropping_season_name' => $season . ' ' . $year
            ] );
        }

        return redirect()->back();
    }
    /**
     * Reports for Seed Request Export to Excel file.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function seedRequestExportToExcel()
    {
        $inventoryId = $this->request->getPost( 'inventory_id' );
        $seedName    = $this->request->getPost( 'seed_name' );

        if ( !$inventoryId ) {
            return $this->response->setBody( 'No inventory ID provided.' );
        }

        // Extract type like "Rice" from "RC18(Rice)"
        if ( preg_match( '/\((.*?)\)/', $seedName, $match ) ) {
            $seedType = $match[ 1 ];
        } else {
            $seedType = $seedName;
        }

        if ( !session()->has( 'selected_cropping_season_id' ) ) {
            if ( session()->has( 'current_season_id' ) && session()->has( 'current_season_name' ) ) {
                session()->set( [
                    'selected_cropping_season_id'   => session()->get( 'current_season_id' ),
                    'selected_cropping_season_name' => session()->get( 'current_season_name' )
                ] );
            }
        }

        $selectedSeasonId     = session()->get( 'selected_cropping_season_id' );
        $selectedSeasonName   = session()->get( 'selected_cropping_season_name' );
        $selectedBarangayName = session()->get( 'selected_report_barangay_name' );

        $seedRequestsModel = new SeedRequestsModel();

        $requests = $seedRequestsModel
            ->select( [
                'client_info.last_name',
                'client_info.first_name',
                'client_info.middle_name',
                'client_info.suffix_and_ext',
                'client_info.brgy',
                'client_info.rsbsa_ref_no',
                'client_info.name_land_owner',
                'client_info.farm_area'
            ] )
            ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
            ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
            ->where( 'inventory.inventory_tbl_id', $inventoryId )
            ->where( 'inventory.cropping_season_tbl_id', $selectedSeasonId )
            ->where( 'client_info.brgy', $selectedBarangayName )
            ->orderBy( 'client_info.last_name', 'ASC' )
            ->findAll();

        if ( empty( $requests ) ) {
            session()->setFlashdata( 'swal', [
                'title'             => 'No Data',
                'text'              => 'No available data. Please try again later.',
                'icon'              => 'info',
                'confirmButtonText' => 'OK'
            ] );
            return redirect()->back();
        }

        // ✅ Create Excel Spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Insert Logo
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath( FCPATH . 'templates/img/icon.png' );
        $drawing->setCoordinates( 'D1' );
        $sheet->getRowDimension( 1 )->setRowHeight( 60 ); // match the image height
        $drawing->setHeight( 60 );
        $drawing->setOffsetX( 40 );        // left padding inside D1
        $drawing->setOffsetY( 8 );        // vertical alignment
        $drawing->setWorksheet( $sheet );


        // Header Text - Centered A-J
        // Merge the cells for the single-row header
        $sheet->mergeCells( 'B1:H1' );

        // Set the value with line breaks
        $sheet->setCellValue( 'B1', "Republic of the Philippines\nProvince of Eastern Samar\nMunicipality of Oras" );

        // Enable text wrap so the line breaks show
        $sheet->getStyle( 'B1' )->getAlignment()
            ->setHorizontal( \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER )
            ->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )
            ->setWrapText( true );

        // Make font bold and size 12
        $sheet->getStyle( 'B1' )->getFont()->setBold( false )->setSize( 12 );

        // Barangay
        $sheet->mergeCells( 'A3:H3' )->setCellValue( 'A3', 'Barangay: ' . $selectedBarangayName );

        // Merge cells for left part (Seed Request)
        $sheet->mergeCells( 'A4:C4' )->setCellValue( 'A4', 'REQUEST FOR ' . strtoupper( $seedType ) . ' SEEDS' );

        // Merge cells for right/center part (Cropping Season)
        $sheet->mergeCells( 'D4:G4' )->setCellValue( 'D4', 'Cropping Season: ' . $selectedSeasonName );

        // Styles
        $sheet->getStyle( 'A3' )->getFont()->setBold( false )->setSize( 12 );
        $sheet->getStyle( 'A3' )->getAlignment()->setHorizontal( \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER );


        $sheet->getStyle( 'A4' )->getFont()->setBold( true )->setSize( 12 );
        $sheet->getStyle( 'A4' )->getAlignment()->setHorizontal( \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT );

        $sheet->getStyle( 'D4' )->getFont()->setBold( false )->setSize( 12 );
        $sheet->getStyle( 'D4' )->getAlignment()->setHorizontal( \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER );



        // Table Headers
        $headers  = [ 'NO.', 'LAST NAME', 'FIRST NAME', 'MIDDLE NAME', 'EXT.', 'RSBSA REF NO.', 'NAME OF LAND OWNER', 'FARM AREA (Ha)' ];
        $col      = 'A';
        $startRow = 5;
        foreach ( $headers as $header ) {
            $sheet->setCellValue( $col . $startRow, $header );
            $sheet->getStyle( $col . $startRow )->getFont()->setBold( true );
            $sheet->getStyle( $col . $startRow )->getAlignment()->setHorizontal( \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER );
            $sheet->getColumnDimension( $col )->setAutoSize( true );
            $col++;
        }

        // Fill Data
        $rowNum  = $startRow + 1;
        $counter = 1;
        foreach ( $requests as $entry ) {
            $sheet->setCellValue( 'A' . $rowNum, $counter++ );
            $sheet->setCellValue( 'B' . $rowNum, $entry[ 'last_name' ] );
            $sheet->setCellValue( 'C' . $rowNum, $entry[ 'first_name' ] );
            $sheet->setCellValue( 'D' . $rowNum, empty( $entry[ 'middle_name' ] ) ? 'N/A' : $entry[ 'middle_name' ] );
            $sheet->setCellValue( 'E' . $rowNum, empty( $entry[ 'suffix_and_ext' ] ) ? 'N/A' : $entry[ 'suffix_and_ext' ] );
            $sheet->setCellValue( 'F' . $rowNum, $entry[ 'rsbsa_ref_no' ] );
            $sheet->setCellValue( 'G' . $rowNum, $entry[ 'name_land_owner' ] );
            $sheet->setCellValue( 'H' . $rowNum, $entry[ 'farm_area' ] );

            // Set alignment left for the entire row
            foreach ( range( 'A', 'H' ) as $col ) {
                $sheet->getStyle( $col . $rowNum )
                    ->getAlignment()
                    ->setHorizontal( \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT );
            }
            $rowNum++;
        }

        // Apply Borders
        $sheet->getStyle( 'A' . $startRow . ':H' . ( $rowNum - 1 ) )
            ->getBorders()->getAllBorders()
            ->setBorderStyle( \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN );

        // Filename and Output
        $filename = 'Seed_Request_Report_for_' . $seedType . '-' . $selectedBarangayName . '-' . $selectedSeasonName . '.xlsx';
        header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx( $spreadsheet );
        $writer->save( 'php://output' );
        exit;
    }

    /**
     * Reports for beneficiaries Export to Excel file.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function beneficiariesExportToExcel()
    {
        $inventoryId = $this->request->getPost( 'beneficiaries_inventory_id' );
        $seedName    = $this->request->getPost( 'beneficiaries_seed_name' );

        if ( !$inventoryId ) {
            return $this->response->setBody( 'No inventory ID provided.' );
        }

        // Extract type like "Rice" from "RC18(Rice)"
        if ( preg_match( '/\((.*?)\)/', $seedName, $match ) ) {
            $seedType = $match[ 1 ];
        } else {
            $seedType = $seedName;
        }

        if ( !session()->has( 'selected_cropping_season_id' ) ) {
            if ( session()->has( 'current_season_id' ) && session()->has( 'current_season_name' ) ) {
                session()->set( [
                    'selected_cropping_season_id'   => session()->get( 'current_season_id' ),
                    'selected_cropping_season_name' => session()->get( 'current_season_name' )
                ] );
            }
        }

        $selectedSeasonId     = session()->get( 'selected_cropping_season_id' );
        $selectedSeasonName   = session()->get( 'selected_cropping_season_name' );
        $selectedBarangayName = session()->get( 'selected_report_barangay_name' );

        $beneficiariesModel = new BeneficiariesModel();

        $requests = $beneficiariesModel
            ->select( [
                'beneficiaries.*',
                'client_info.*',
                'users.contact_no',
                'inventory.inventory_tbl_id',
                'inventory.seed_name',
                'inventory.seed_class',
                'cropping_season.season',
                'cropping_season.year'
            ] )
            ->join( 'seed_requests', 'seed_requests.seed_requests_tbl_id = beneficiaries.seed_requests_tbl_id' )
            ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
            ->join( 'users', 'users.users_tbl_id = client_info.users_tbl_id' )
            ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
            ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
            ->where( 'inventory.inventory_tbl_id', $inventoryId )
            ->where( 'cropping_season.cropping_season_tbl_id', $selectedSeasonId )
            ->where( 'client_info.brgy', $selectedBarangayName )
            ->orderBy( 'client_info.last_name', 'ASC' )
            ->findAll();

        if ( empty( $requests ) ) {
            session()->setFlashdata( 'swal', [
                'title'             => 'No Data',
                'text'              => 'No available data. Please try again later.',
                'icon'              => 'info',
                'confirmButtonText' => 'OK'
            ] );
            return redirect()->back();
        }

        // ✅ Create Excel Spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Insert Logo
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath( FCPATH . 'templates/img/icon.png' );
        $drawing->setCoordinates( 'H1' );
        $sheet->getRowDimension( 1 )->setRowHeight( 60 ); // match the image height
        $drawing->setHeight( 60 );
        $drawing->setOffsetX( 90 );
        $drawing->setOffsetY( 8 );        // vertical alignment
        $drawing->setWorksheet( $sheet );


        // Header Text - Centered A-J
        // Merge the cells for the single-row header
        $sheet->mergeCells( 'B1:O1' );

        // Set the value with line breaks
        $sheet->setCellValue( 'B1', "Republic of the Philippines\nProvince of Eastern Samar\nMunicipality of Oras" );

        // Enable text wrap so the line breaks show
        $sheet->getStyle( 'B1' )->getAlignment()
            ->setHorizontal( \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER )
            ->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )
            ->setWrapText( true );

        // Make font bold and size 12
        $sheet->getStyle( 'B1' )->getFont()->setBold( false )->setSize( 12 );

        // Barangay
        $sheet->mergeCells( 'A3:O3' )->setCellValue( 'A3', 'Barangay: ' . $selectedBarangayName );

        // Merge cells for left part (Seed Request)
        $sheet->mergeCells( 'A4:C4' )->setCellValue( 'A4', 'BENEFICIARIES FOR ' . strtoupper( $seedType ) . ' SEEDS' );

        // Merge cells for right/center part (Cropping Season)
        $sheet->mergeCells( 'D4:N4' )->setCellValue( 'D4', 'Cropping Season: ' . $selectedSeasonName );

        // Styles
        $sheet->getStyle( 'A3' )->getFont()->setBold( false )->setSize( 12 );
        $sheet->getStyle( 'A3' )->getAlignment()->setHorizontal( \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER );


        $sheet->getStyle( 'A4' )->getFont()->setBold( true )->setSize( 12 );
        $sheet->getStyle( 'A4' )->getAlignment()->setHorizontal( \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT );

        $sheet->getStyle( 'D4' )->getFont()->setBold( false )->setSize( 12 );
        $sheet->getStyle( 'D4' )->getAlignment()->setHorizontal( \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER );



        // Multi-row Table Headers
        $startRow = 5;

        // First row
        $sheet->setCellValue( 'A' . $startRow, 'No.' );
        $sheet->setCellValue( 'B' . $startRow, 'RSBSA Ref No.' );
        $sheet->setCellValue( 'C' . $startRow, 'Name of Farmer' );
        $sheet->setCellValue( 'G' . $startRow, 'Barangay' );
        $sheet->setCellValue( 'H' . $startRow, 'Municipality' );
        $sheet->setCellValue( 'I' . $startRow, 'Province' );
        $sheet->setCellValue( 'J' . $startRow, "Birthdate\n(mm/dd/yyyy)" );
        $sheet->setCellValue( 'K' . $startRow, "Gender\nMale/Female" );
        $sheet->setCellValue( 'L' . $startRow, "Contact No.\n(Mobile No.)" );
        $sheet->setCellValue( 'M' . $startRow, "Farm Area\n(Hectares)" );
        $sheet->setCellValue( 'N' . $startRow, 'Voucher' );

        // Merge for first row
        $sheet->mergeCells( 'A5:A6' ); // No.
        $sheet->mergeCells( 'B5:B6' ); // RSBSA Ref
        $sheet->mergeCells( 'C5:F5' ); // Name of Farmer
        $sheet->mergeCells( 'G5:G6' ); // Barangay
        $sheet->mergeCells( 'H5:H6' ); // Municipality
        $sheet->mergeCells( 'I5:I6' ); // Province
        $sheet->mergeCells( 'J5:J6' ); // Birthdate
        $sheet->mergeCells( 'K5:K6' ); // Gender
        $sheet->mergeCells( 'L5:L6' ); // Contact No
        $sheet->mergeCells( 'M5:M6' ); // Farm Area
        $sheet->mergeCells( 'N5:O5' ); // Voucher Ref placeholder

        // Second row
        $sheet->setCellValue( 'C6', 'Last Name' );
        $sheet->setCellValue( 'D6', 'First Name' );
        $sheet->setCellValue( 'E6', 'Middle Name' );
        $sheet->setCellValue( 'F6', "Suffix\n& Ext." );


        $sheet->setCellValue( 'N6', 'Reference No' );
        $sheet->setCellValue( 'O6', 'Date Received' );


        // Style all header cells
        foreach ( range( 'A', 'O' ) as $col ) {
            foreach ( [ 5, 6 ] as $row ) {
                $sheet->getStyle( $col . $row )->getFont()->setBold( true );
                $sheet->getStyle( $col . $row )->getAlignment()
                    ->setHorizontal( \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER )
                    ->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )
                    ->setWrapText( true ); // ✅ Enable wrap text
                $sheet->getColumnDimension( $col )->setAutoSize( true );
            }
        }

        // Optional: increase row height to show wrapped text properly
        $sheet->getRowDimension( 5 )->setRowHeight( 30 );
        $sheet->getRowDimension( 6 )->setRowHeight( 30 );


        // Fill Data
        $rowNum  = $startRow + 2;
        $counter = 1;
        foreach ( $requests as $entry ) {

            $parts = explode( '_', $entry[ 'qr_code' ] );
            $ref   = $parts[ 3 ];

            $sheet->setCellValue( 'A' . $rowNum, $counter++ );
            $sheet->setCellValue( 'B' . $rowNum, $entry[ 'rsbsa_ref_no' ] );
            $sheet->setCellValue( 'C' . $rowNum, $entry[ 'last_name' ] );
            $sheet->setCellValue( 'D' . $rowNum, $entry[ 'first_name' ] );
            $sheet->setCellValue( 'E' . $rowNum, !empty( $entry[ 'middle_name' ] ) ? $entry[ 'middle_name' ] : 'N/A' );
            $sheet->setCellValue( 'F' . $rowNum, !empty( $entry[ 'suffix_and_ext' ] ) ? $entry[ 'suffix_and_ext' ] : 'N/A' );
            $sheet->setCellValue( 'G' . $rowNum, $entry[ 'brgy' ] ?? 'N/A' );
            $sheet->setCellValue( 'H' . $rowNum, $entry[ 'mun' ] ?? 'N/A' );
            $sheet->setCellValue( 'I' . $rowNum, $entry[ 'prov' ] ?? 'N/A' );
            $sheet->setCellValue( 'J' . $rowNum, !empty( $entry[ 'b_date' ] ) ? ( new DateTime( $entry[ 'b_date' ] ) )->format( 'F j, Y' ) : 'N/A' );
            $sheet->setCellValue( 'K' . $rowNum, $entry[ 'gender' ] ?? 'N/A' );
            $sheet->setCellValue( 'L' . $rowNum, $entry[ 'contact_no' ] ?? 'N/A' );
            $sheet->setCellValue( 'M' . $rowNum, $entry[ 'farm_area' ] ?? 'N/A' );
            $sheet->setCellValue( 'N' . $rowNum, $ref ?? 'N/A' );
            $sheet->setCellValue( 'O' . $rowNum, !empty( $entry[ 'date_time_received' ] ) ? ( new DateTime( $entry[ 'date_time_received' ] ) )->format( 'F j, Y h:i A' ) : 'N/A' );

            // Set alignment left for the entire row
            foreach ( range( 'A', 'O' ) as $col ) {
                $sheet->getStyle( $col . $rowNum )
                    ->getAlignment()
                    ->setHorizontal( \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT );
            }

            $rowNum++;
        }


        // Apply Borders
        $sheet->getStyle( 'A' . $startRow . ':O' . ( $rowNum - 1 ) )
            ->getBorders()->getAllBorders()->setBorderStyle( \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN );

        // Output
        $filename = 'Beneficiaries_Report_for_' . $seedType . '-' . $selectedBarangayName . '-' . $selectedSeasonName . '.xlsx';
        header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx( $spreadsheet );
        $writer->save( 'php://output' );
        exit;
    }


    /**
     * Reports for Seed Request Export to PDF file.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function seedRequestExportToPDF()
    {
        /**
         * Renders the header for the PDF report.
         *
         * @param string $src The source of the logo image.
         * @param string $brgy The barangay name.
         * @param string $seedType The type of seed.
         * @param string $seasonName The name of the cropping season.
         * @return string HTML content for the header.
         */
        function renderHead( $src, $brgy, $seedType, $seasonName )
        {
            return '
            <div style="width: 100%; text-align: center; margin-bottom: 2px; font-size: 12px;">
                <div style="display: inline-block; vertical-align: middle;">
                    <img src="' . $src . '" style="width: 60px; height: auto;">
                </div>
                <div style="display: inline-block; padding-left: 5px; line-height: 1.5; vertical-align: middle;">
                    Republic of the Philippines<br>
                    Province of Eastern Samar<br>
                    Municipality of Oras
                </div>
            </div>
            <div style="text-align: center; font-size: 12px; margin-bottom: 4px;">
                Barangay: ' . esc( $brgy ) . '
            </div>
            <div style="position: relative; width: 100%; height: 20px; margin-bottom: 2px; font-size: 12px;">
                <div style="position: absolute; left: 0; font-weight: bold;">
                    REQUEST FOR ' . strtoupper( esc( $seedType ) ) . ' SEEDS
                </div>
                <div style="position: absolute; left: 50%; transform: translateX(-50%);">
                    Cropping Season: ' . $seasonName . '
                </div>
            </div>';
        }

        $inventoryId = $this->request->getPost( 'inventory_id' );
        $seedName    = $this->request->getPost( 'seed_name' );

        if ( !$inventoryId ) {
            return $this->response->setBody( 'No inventory ID provided.' );
        }

        // Extract type like "Rice" from "RC18(Rice)"
        if ( preg_match( '/\((.*?)\)/', $seedName, $match ) ) {
            $seedType = $match[ 1 ]; // inside the ()
        } else {
            $seedType = $seedName; // use as is
        }

        if ( !session()->has( 'selected_cropping_season_id' ) ) {
            // Check if fallback cropping_season_id is available
            if ( session()->has( 'current_season_id' ) && session()->has( 'current_season_name' ) ) {
                session()->set( [
                    'selected_cropping_season_id'   => session()->get( 'current_season_id' ),
                    'selected_cropping_season_name' => session()->get( 'current_season_name' )
                ] );
            }
        }
        $selectedSeasonId     = session()->get( 'selected_cropping_season_id' );
        $selectedSeasonName   = session()->get( 'selected_cropping_season_name' );
        $selectedBarangayName = session()->get( 'selected_report_barangay_name' );
        $seedRequestsModel    = new SeedRequestsModel();

        $requests = $seedRequestsModel
            ->select( [
                'client_info.last_name',
                'client_info.first_name',
                'client_info.middle_name',
                'client_info.suffix_and_ext',
                'client_info.brgy',
                'client_info.rsbsa_ref_no',
                'client_info.name_land_owner',
                'client_info.farm_area',
                'cropping_season.season',
                'cropping_season.year'
            ] )
            ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
            ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
            ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
            ->where( 'inventory.inventory_tbl_id', $inventoryId )
            ->where( 'cropping_season.cropping_season_tbl_id', $selectedSeasonId )
            ->where( 'client_info.brgy', $selectedBarangayName )
            ->orderBy( 'client_info.brgy', 'ASC' )
            ->orderBy( 'client_info.last_name', 'ASC' )
            ->findAll();


        if ( empty( $requests ) ) {
            session()->setFlashdata( 'swal', [
                'title'             => 'No Data',
                'text'              => 'No available data. Please try again later.',
                'icon'              => 'info',
                'confirmButtonText' => 'OK'
            ] );
            return redirect()->back();
        }

        $html = '<style>
            @page {
                margin-top: 0.5in;
                margin-bottom: 1.5in;
                margin-left: 1in;
                margin-right: 1in;
            }
            @font-face {
                font-family: Calibri;
                src: local("Calibri"), local("Calibri Regular"), url("https://fonts.cdnfonts.com/s/12028/Calibri.woff") format("woff");
            }
            body, table, th, td {
                font-family: Calibri, sans-serif;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 12px;
            }
            th, td {
                border: 1px solid #333;
                padding: 2px 4px;
                vertical-align: middle;
            }
            th {
                background-color: transparent;
            }
        </style>';

        $imgPath = FCPATH . 'templates/img/icon.png';
        $imgData = base64_encode( file_get_contents( $imgPath ) );
        $src     = 'data:image/png;base64,' . $imgData;

        // Group by barangay
        $groupedRequests = [];
        foreach ( $requests as $row ) {
            $groupedRequests[ $row[ 'brgy' ] ][] = $row;
        }

        $first = true;
        foreach ( $groupedRequests as $brgy => $entries ) {

            if ( !$first ) {
                $html .= '<div style="page-break-before: always;"></div>';
            }
            $first = false;

            $html .= renderHead( $src, $brgy, $seedType, $selectedSeasonName );

            $counter  = 1;
            $rowCount = 0;

            foreach ( $entries as $entry ) {
                if ( $rowCount % 20 == 0 ) {
                    if ( $rowCount > 0 ) {
                        $html .= '</tbody></table>';
                        $html .= '<div style="page-break-before: always;"></div>';

                        $html .= renderHead( $src, $brgy, $seedType, $selectedSeasonName );
                    }
                    // Table header
                    $html .= '<table>
                    <thead>
                        <tr>
                            <th rowspan="2">NO.</th>
                            <th colspan="4" style="text-align: center;">NAME OF FARMER</th>
                            <th rowspan="2">RSBSA REFERENCE NO.</th>
                            <th rowspan="2">NAME OF LAND OWNER</th>
                            <th rowspan="2">FARM<br>AREA<br>(Hectares)</th>
                        </tr>
                        <tr>
                            <th>LAST NAME</th>
                            <th>FIRST NAME</th>
                            <th>MIDDLE NAME</th>
                            <th>EXT.<br>NAME</th>
                        </tr>
                    </thead>
                    <tbody>';
                }

                $html .= '<tr>
                <td>' . $counter++ . '</td>
                <td>' . esc( $entry[ 'last_name' ] ) . '</td>
                <td>' . esc( $entry[ 'first_name' ] ) . '</td>
                <td>' . ( empty( $entry[ 'middle_name' ] ) ? 'N/A' : esc( $entry[ 'middle_name' ] ) ) . '</td>
                <td>' . ( empty( $entry[ 'suffix_and_ext' ] ) ? 'N/A' : esc( $entry[ 'suffix_and_ext' ] ) ) . '</td>
                <td>' . esc( $entry[ 'rsbsa_ref_no' ] ) . '</td>
                <td>' . esc( $entry[ 'name_land_owner' ] ) . '</td>
                <td>' . esc( $entry[ 'farm_area' ] ) . '</td>
            </tr>';

                $rowCount++;
            }

            $html .= '</tbody></table>';
        }

        $options = new Options();
        $options->set( 'isHtml5ParserEnabled', true );
        $options->set( 'isRemoteEnabled', true );

        $dompdf = new Dompdf( $options );
        $dompdf->loadHtml( $html );
        $dompdf->setPaper( 'A4', 'landscape' );
        $dompdf->render();


        $filename = 'Seed_Request_Report_for_' . $seedType . '-' . $selectedBarangayName . '-' . $selectedSeasonName . '.pdf';

        $dompdf->stream( $filename, [ 'Attachment' => true ] );
        exit;
    }
    /**
     * Reports for Beneficiaries Export to PDF file.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function beneficiariesExportToPDF()
    {
        /**
         * Renders the header for the PDF report.
         *
         * @param string $src The source of the logo image.
         * @param string $brgy The barangay name.
         * @param string $seedType The type of seed.
         * @param string $seasonName The name of the cropping season.
         * @return string HTML content for the header.
         */
        function renderHeader( $src, $brgy, $seedType, $seasonName )
        {
            return '
                <div style="width: 100%; text-align: center; margin-bottom: 2px; font-size: 12px;">
                    <div style="display: inline-block; vertical-align: middle;">
                        <img src="' . $src . '" style="width: 60px; height: auto;">
                    </div>
                    <div style="display: inline-block; padding-left: 5px; line-height: 1.5; vertical-align: middle;">
                        Republic of the Philippines<br>
                        Province of Eastern Samar<br>
                        Municipality of Oras
                    </div>
                </div>
                <div style="text-align: center; font-size: 12px; margin-bottom: 4px;">
                    Barangay: ' . esc( $brgy ) . '
                </div>
                <div style="position: relative; width: 100%; height: 20px; margin-bottom: 2px; font-size: 12px;">
                    <div style="position: absolute; left: 0; font-weight: bold;">
                        BENEFICIARIES FOR ' . strtoupper( htmlspecialchars( $seedType ) ) . ' SEEDS
                    </div>
                    <div style="position: absolute; left: 50%; transform: translateX(-50%);">
                        Cropping Season: ' . $seasonName . '
                    </div>
                </div>'
            ;
        }

        $inventoryId = $this->request->getPost( 'beneficiaries_inventory_id' );
        $seedName    = $this->request->getPost( 'beneficiaries_seed_name' );

        if ( !$inventoryId ) {
            return $this->response->setBody( 'No inventory ID provided.' );
        }

        // Extract type like "Rice" from "RC18(Rice)"
        if ( preg_match( '/\((.*?)\)/', $seedName, $match ) ) {
            $seedType = $match[ 1 ]; // inside the ()
        } else {
            $seedType = $seedName; // use as is
        }


        if ( !session()->has( 'selected_cropping_season_id' ) ) {
            // Check if fallback cropping_season_id is available
            if ( session()->has( 'current_season_id' ) && session()->has( 'current_season_name' ) ) {
                session()->set( [
                    'selected_cropping_season_id'   => session()->get( 'current_season_id' ),
                    'selected_cropping_season_name' => session()->get( 'current_season_name' )
                ] );
            }
        }
        $selectedSeasonId     = session()->get( 'selected_cropping_season_id' );
        $selectedSeasonName   = session()->get( 'selected_cropping_season_name' );
        $selectedBarangayName = session()->get( 'selected_report_barangay_name' );
        $benefeciariesModel   = new BeneficiariesModel();
        $requests             = $benefeciariesModel
            ->select( [
                'beneficiaries.*',
                'client_info.*',
                'users.contact_no',
                'inventory.inventory_tbl_id',
                'inventory.seed_name',
                'inventory.seed_class',
                'cropping_season.season',
                'cropping_season.year'
            ] )
            ->join( 'seed_requests', 'seed_requests.seed_requests_tbl_id = beneficiaries.seed_requests_tbl_id' )
            ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
            ->join( 'users', 'users.users_tbl_id = client_info.users_tbl_id' )
            ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
            ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
            ->where( 'inventory.inventory_tbl_id', $inventoryId )
            ->where( 'cropping_season.cropping_season_tbl_id', $selectedSeasonId )
            ->where( 'client_info.brgy', $selectedBarangayName )
            ->orderBy( 'client_info.brgy', 'ASC' )
            ->orderBy( 'client_info.last_name', 'ASC' )
            ->findAll();


        if ( empty( $requests ) ) {
            session()->setFlashdata( 'swal', [
                'title'             => 'No Data',
                'text'              => 'No available data. Please try again later.',
                'icon'              => 'info',
                'confirmButtonText' => 'OK'
            ] );
            return redirect()->back();
        }

        $html = '<style>
            @page {
                margin-top: 0.5in;
                margin-bottom: 1.5in;
                margin-left: 0.5in;
                margin-right: 0.5in;
            }
            @font-face {
                font-family: Calibri;
                src: local("Calibri"), local("Calibri Regular"), url("https://fonts.cdnfonts.com/s/12028/Calibri.woff") format("woff");
            }
            body, table, th, td {
                font-family: Calibri, sans-serif;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 12px;
            }
            th, td {
                border: 1px solid #333;
                padding: 1px 4px;
                vertical-align: middle;
            }
            th {
                background-color: transparent;
            }
        </style>';

        $imgPath = FCPATH . 'templates/img/icon.png';
        $imgData = base64_encode( file_get_contents( $imgPath ) );
        $src     = 'data:image/png;base64,' . $imgData;

        // Group by barangay
        $groupedRequests = [];
        foreach ( $requests as $row ) {
            $groupedRequests[ $row[ 'brgy' ] ][] = $row;
        }

        $first = true;
        foreach ( $groupedRequests as $brgy => $entries ) {
            if ( !$first ) {
                $html .= '<div style="page-break-before: always;"></div>';
            }
            $first = false;

            $html .= renderHeader( $src, $brgy, $seedType, $selectedSeasonName );

            $counter  = 1;
            $rowCount = 0;

            foreach ( $entries as $entry ) {

                $parts = explode( '_', $entry[ 'qr_code' ] );
                $ref   = $parts[ 3 ];

                if ( $rowCount % 15 == 0 ) {
                    if ( $rowCount > 0 ) {
                        $html .= '</tbody></table>';
                        $html .= '<div style="page-break-before: always;"></div>';

                        $html .= renderHeader( $src, $brgy, $seedType, $selectedSeasonName );
                    }
                    $html .= '<table>
                        <thead>
                            <tr>
                                <th rowspan="2">No.</th>
                                <th rowspan="2">RSBSA Reference No.</th>
                                <th colspan="4" class="text-center">Name of Farmer</th>
                                <th rowspan="2">Barangay</th>
                                <th rowspan="2">Municipality</th>
                                <th rowspan="2">Province</th>
                                <th rowspan="2">Birthdate<br>(mm/dd/yyyy)</th>
                                <th rowspan="2">Gender<br>Male/Female</th>
                                <th rowspan="2">Contact No.<br>(Mobile No.)</th>
                                <th rowspan="2">Farm Area<br>(Hectares)</th>
                                <th colspan="2" style="text-align: center;">Voucher</th>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Suffix & Ext.</th>
                                <th>Reference No</th>
                                <th>Date Received</th>
                            </tr>
                        </thead>
                    <tbody>';

                }

                $html .= '<tr>
                <td>' . $counter++ . '</td>
                <td>' . esc( $entry[ 'rsbsa_ref_no' ] ) . '</td>
                <td>' . esc( $entry[ 'last_name' ] ) . '</td>
                <td>' . esc( $entry[ 'first_name' ] ) . '</td>
                <td>' . ( !empty( $entry[ 'middle_name' ] ) ? esc( $entry[ 'middle_name' ] ) : 'N/A' ) . '</td>
                <td>' . ( !empty( $entry[ 'suffix_and_ext' ] ) ? esc( $entry[ 'suffix_and_ext' ] ) : 'N/A' ) . '</td>
                <td>' . esc( $entry[ 'brgy' ] ?? 'N/A' ) . '</td>
                <td>' . esc( $entry[ 'mun' ] ) . '</td>
                <td>' . esc( $entry[ 'prov' ] ) . '</td>
                <td>' . (
                    !empty( $entry[ 'b_date' ] ) && DateTime::createFromFormat( 'Y-m-d', $entry[ 'b_date' ] )
                    ? DateTime::createFromFormat( 'Y-m-d', $entry[ 'b_date' ] )->format( 'F j, Y' )
                    : '—'
                ) . '</td>
                <td>' . esc( $entry[ 'gender' ] ) . '</td>
                <td>' . esc( $entry[ 'contact_no' ] ?? 'N/A' ) . '</td>
                <td>' . esc( $entry[ 'farm_area' ] ) . '</td>
                <td>' . esc( $ref ?? "N/A" ) . '</td>
                <td>' . (
                    !empty( $entry[ 'date_time_received' ] ) && DateTime::createFromFormat( 'm-d-Y h:i A', $entry[ 'date_time_received' ] )
                    ? DateTime::createFromFormat( 'm-d-Y h:i A', $entry[ 'date_time_received' ] )->format( 'F j, Y' ) . '<br><small>' .
                    DateTime::createFromFormat( 'm-d-Y h:i A', $entry[ 'date_time_received' ] )->format( 'h:i A' ) . '</small>'
                    : 'N/A'
                ) . '</td>
            </tr>';

                $rowCount++;
            }

            $html .= '</tbody></table>';
        }


        $options = new Options();
        $options->set( 'isHtml5ParserEnabled', true );
        $options->set( 'isRemoteEnabled', true );

        $dompdf = new Dompdf( $options );
        $dompdf->loadHtml( $html );
        $dompdf->setPaper( [ 0, 0, 612, 936 ], 'landscape' );
        $dompdf->render();

        // return $this->response
        //     ->setContentType( 'application/pdf' )
        //     ->setBody( $dompdf->output() );

        // $cleanSeasonName = str_replace( [ ' ', '/' ], '_', $selectedSeasonName . ' ' . $selectedBarangayName ); // remove spaces or slashes
        $filename = 'Beneficiaries_Report_for_' . $seedType . '-' . $selectedBarangayName . '-' . $selectedSeasonName . '.pdf';

        $dompdf->stream( $filename, [ 'Attachment' => true ] );
        exit;

    }
}
