<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\SeedRequestsModel;
use App\Models\BeneficiariesModel;

class SeedRequestsController extends BaseController
{
    public function approve( $id )
    {
        $rsbsa     = $this->request->getPost( 'rsbsa' );
        $season    = $this->request->getPost( 'season' );
        $year      = $this->request->getPost( 'year' );
        $seedName  = $this->request->getPost( 'seed_name' );
        $seedClass = $this->request->getPost( 'seed_class' );

        $requestModel     = new SeedRequestsModel();
        $beneficiaryModel = new BeneficiariesModel();

        $qrCode = "{$season}-{$year}-{$seedName}-{$seedClass}-{$rsbsa}";

        // Get Philippine time
        $philTime      = new \DateTime( 'now', new \DateTimeZone( 'Asia/Manila' ) );
        $formattedDate = $philTime->format( 'm-d-Y h:i A' );

        $requestModel->update( $id, [ 
            'status'             => 'Approved',
            'date_time_approved' => $formattedDate
        ] );

        $beneficiaryModel->insert( [ 
            'qr_code'              => $qrCode,
            'status'               => 'Pending',
            'seed_requests_tbl_id' => $id
        ] );

        return redirect()->back()->with( 'message', 'Request approved and beneficiary recorded.' );
    }


    public function undoApproved( $id )
    {
        $rsbsa     = $this->request->getPost( 'rsbsa' );
        $season    = $this->request->getPost( 'season' );
        $year      = $this->request->getPost( 'year' );
        $seedName  = $this->request->getPost( 'seed_name' );
        $seedClass = $this->request->getPost( 'seed_class' );

        $qrCode = "{$season}-{$year}-{$seedName}-{$seedClass}-{$rsbsa}";

        $requestModel     = new SeedRequestsModel();
        $beneficiaryModel = new BeneficiariesModel();

        $requestModel->update( $id, [ 
            'status'             => 'Pending',
            'date_time_approved' => null
        ] );

        $beneficiaryModel->where( 'qr_code', $qrCode )->delete();

        return redirect()->back()->with( 'message', 'Approval undone and beneficiary removed.' );
    }


    public function reject( $id )
    {
        $philTime      = new \DateTime( 'now', new \DateTimeZone( 'Asia/Manila' ) );
        $formattedDate = $philTime->format( 'm-d-Y h:i A' );

        $model = new SeedRequestsModel();
        $model->update( $id, [ 
            'status'             => 'Rejected',
            'date_time_rejected' => $formattedDate
        ] );

        return redirect()->back()->with( 'message', 'Request rejected successfully.' );
    }

    public function undoRejected( $id )
    {
        $model = new SeedRequestsModel();

        $model->update( $id, [ 
            'status'             => 'Pending',
            'date_time_rejected' => null,
        ] );

        return redirect()->back()->with( 'message', 'Rejection has been undone.' );
    }

}
