<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\InventoryModel;
use App\Models\SeedRequestsModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Border;
class ReportsController extends BaseController
{
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
}
