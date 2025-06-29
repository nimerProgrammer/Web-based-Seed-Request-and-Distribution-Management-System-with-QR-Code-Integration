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
     * Reports Export to Excel file.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function exportToExcel()
    {
        $inventoryId = $this->request->getPost( 'inventory_id' );

        if ( !$inventoryId ) {
            return $this->response->setBody( 'No inventory ID provided.' );
        }

        $seedRequestsModel = new SeedRequestsModel();

        $requests = $seedRequestsModel
            ->select( [ 
                'client_info.last_name',
                'client_info.first_name',
                'client_info.middle_name',
                'client_info.suffix_and_ext',
                'client_info.rsbsa_ref_no',
                'client_info.name_land_owner',
                'client_info.farm_area',
            ] )
            ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
            ->where( 'seed_requests.inventory_tbl_id', $inventoryId )
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

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $sheet->setTitle( 'Seed Requests' );
        $sheet->getPageSetup()->setOrientation( PageSetup::ORIENTATION_LANDSCAPE );
        $sheet->getPageSetup()->setPaperSize( PageSetup::PAPERSIZE_A4 );

        // Header
        $sheet->fromArray( [ 
            [ 'No.', 'Last Name', 'First Name', 'Middle Name', 'Suffix & Ext.', 'RSBSA Reference No.', 'Name of Land Owner', 'Farm Area (Ha)' ]
        ], null, 'A1' );

        $row = 2;
        $i   = 1;
        foreach ( $requests as $req ) {
            $sheet->fromArray( [ 
                $i++,
                $req[ 'last_name' ],
                $req[ 'first_name' ],
                $req[ 'middle_name' ] ?? 'N/A',
                $req[ 'suffix_and_ext' ] ?? 'N/A',
                $req[ 'rsbsa_ref_no' ],
                $req[ 'name_land_owner' ],
                $req[ 'farm_area' ]
            ], null, "A{$row}" );
            $row++;
        }

        // Auto-size columns
        foreach ( range( 'A', $sheet->getHighestColumn() ) as $col ) {
            $sheet->getColumnDimension( $col )->setAutoSize( true );
        }

        // Add borders to all data
        $lastRow    = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();
        $range      = "A1:{$lastColumn}{$lastRow}";
        $sheet->getStyle( $range )->getBorders()->getAllBorders()->setBorderStyle( Border::BORDER_THIN );

        // Output to browser
        $filename = 'seed_requests_export.xlsx';
        header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
        header( "Content-Disposition: attachment; filename=\"{$filename}\"" );
        header( 'Cache-Control: max-age=0' );

        $writer = new Xlsx( $spreadsheet );
        $writer->save( 'php://output' );
        exit;
    }

    /**
     * Reports Export to PDF file.
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
        $selectedSeasonId   = session()->get( 'selected_cropping_season_id' );
        $selectedSeasonName = session()->get( 'selected_cropping_season_name' );

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
                'client_info.farm_area',
                'cropping_season.season',
                'cropping_season.year'
            ] )
            ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
            ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
            ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
            ->where( 'seed_requests.inventory_tbl_id', $inventoryId )
            ->where( 'cropping_season.cropping_season_tbl_id', $selectedSeasonId )
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

            $html .= renderHeader( $src, $brgy, $seedType, $selectedSeasonName );

            $counter  = 1;
            $rowCount = 0;

            foreach ( $entries as $entry ) {
                if ( $rowCount % 20 == 0 ) {
                    if ( $rowCount > 0 ) {
                        $html .= '</tbody></table>';
                        $html .= '<div style="page-break-before: always;"></div>';

                        $html .= renderHeader( $src, $brgy, $seedType, $selectedSeasonName );
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

        return $this->response
            ->setContentType( 'application/pdf' )
            ->setBody( $dompdf->output() );
    }
    /**
     * Beneficiaries Export to PDF file.
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
                        REQUEST FOR ' . strtoupper( esc( $seedType ) ) . ' SEEDS
                    </div>
                    <div style="position: absolute; left: 50%; transform: translateX(-50%);">
                        Cropping Season: ' . $seasonName . '
                    </div>
                </div>'
            ;
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
        $selectedSeasonId   = session()->get( 'selected_cropping_season_id' );
        $selectedSeasonName = session()->get( 'selected_cropping_season_name' );

        $benefeciariesModel = new BeneficiariesModel();
        $requests           = $benefeciariesModel
            ->select( [ 
                'beneficiaries.*',
                'client_info.rsbsa_ref_no',
                'client_info.last_name',
                'client_info.first_name',
                'client_info.middle_name',
                'client_info.suffix_and_ext',
                'client_info.brgy',
                'client_info.mun',
                'client_info.prov',
                'client_info.b_date',
                'client_info.gender',
                'client_info.farm_area',
                'client_info.name_land_owner',
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
            ->where( 'seed_requests.inventory_tbl_id', $inventoryId )
            ->where( 'cropping_season.cropping_season_tbl_id', $selectedSeasonId )
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

            $html .= renderHeader( $src, $brgy, $seedType, $selectedSeasonName );

            $counter  = 1;
            $rowCount = 0;

            foreach ( $entries as $entry ) {
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
                                <th rowspan="2">Last Name</th>
                                <th rowspan="2">First Name</th>
                                <th rowspan="2">Middle Name</th>
                                <th rowspan="2">Suffix & Ext.</th>
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
                <td>' . ( !empty( $entry[ 'middle_name' ] ) ? esc( $entry[ 'middle_name' ] ) : '—' ) . '</td>
                <td>' . ( !empty( $entry[ 'suffix_and_ext' ] ) ? esc( $entry[ 'suffix_and_ext' ] ) : '—' ) . '</td>
                <td>' . esc( $entry[ 'brgy' ] ?? '—' ) . '</td>
                <td>' . esc( $entry[ 'mun' ] ) . '</td>
                <td>' . esc( $entry[ 'prov' ] ) . '</td>
                <td>' . (
                    !empty( $entry[ 'b_date' ] ) && DateTime::createFromFormat( 'm-d-Y', $entry[ 'b_date' ] )
                    ? DateTime::createFromFormat( 'm-d-Y', $entry[ 'b_date' ] )->format( 'F j, Y' )
                    : '—'
                ) . '</td>
                <td>' . esc( $entry[ 'gender' ] ) . '</td>
                <td>' . esc( $entry[ 'contact_no' ] ?? '—' ) . '</td>
                <td>' . esc( $entry[ 'farm_area' ] ) . '</td>
                <td>' . esc( $entry[ 'ref_no' ] ) . '</td>
                <td>' . (
                    !empty( $entry[ 'date_time_received' ] ) && DateTime::createFromFormat( 'm-d-Y h:i A', $entry[ 'date_time_received' ] )
                    ? DateTime::createFromFormat( 'm-d-Y h:i A', $entry[ 'date_time_received' ] )->format( 'F j, Y' ) . '<br><small>' .
                    DateTime::createFromFormat( 'm-d-Y h:i A', $entry[ 'date_time_received' ] )->format( 'h:i A' ) . '</small>'
                    : '—'
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

        return $this->response
            ->setContentType( 'application/pdf' )
            ->setBody( $dompdf->output() );
    }
}
