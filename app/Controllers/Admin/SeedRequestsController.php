<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\SeedRequestsModel;
use App\Models\BeneficiariesModel;
use App\Models\LogsModel;

class SeedRequestsController extends BaseController
{
    /**
     * Sets the selected barangay for seed requests in the session.
     *
     * This method processes the selected barangay data from the request,
     * splits it into ID and name, and stores them in the session.
     *
     * @return ResponseInterface Redirects to the seeds requests page after setting the barangay.
     */
    public function setBarangayView()
    {
        $data = $this->request->getPost( 'barangay_data' );

        if ( $data ) {
            session()->set( 'selected_seedrequests_barangay_name', $data );
        }

        return redirect()->to( base_url( '/admin/seedsRequests' ) );
    }

    /**
     * Approves a seed request by ID and records the beneficiary.
     *
     * @param int $id The ID of the seed request to approve.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function approve( $id )
    {
        $rsbsa     = $this->request->getPost( 'rsbsa' );
        $season    = $this->request->getPost( 'season' );
        $year      = $this->request->getPost( 'year' );
        $seedName  = $this->request->getPost( 'seed_name' );
        $seedClass = $this->request->getPost( 'seed_class' );

        $requestModel     = new SeedRequestsModel();
        $beneficiaryModel = new BeneficiariesModel();
        $logsModel        = new LogsModel();

        $qrCode = "$season-$year-$seedName-$seedClass-$rsbsa";

        $philTime      = new \DateTime( 'now', new \DateTimeZone( 'Asia/Manila' ) );
        $formattedDate = getPhilippineTimeFormatted();

        // Generate date code (yyyymmdd) from Philippine time
        $dateCode = $philTime->format( 'mdY' );

        // Generate random 6-character alphanumeric code
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $refArray   = str_split( $characters );
        shuffle( $refArray );
        $randomCode = implode( '', array_slice( $refArray, 0, 12 ) );

        // Build final reference code
        $refCode = 'REF-' . $dateCode . '-' . strtoupper( $seedName ) . '-' . $randomCode;

        $requestModel->update( $id, [ 
            'status'             => 'Approved',
            'date_time_approved' => $formattedDate
        ] );

        $beneficiaryModel->insert( [ 
            'qr_code'              => $qrCode,
            'ref_no'               => $refCode,
            'status'               => 'For Receiving',
            'seed_requests_tbl_id' => $id
        ] );

        /* Staff Fullname */
        $staffFullName = session( 'user_fullname' );

        $fullName = ucwords( strtolower( trim(
            $this->request->getPost( 'first_name' ) .
            ( $this->request->getPost( 'middle_name' ) ? ' ' . $this->request->getPost( 'middle_name' ) : '' ) .
            ' ' . $this->request->getPost( 'last_name' ) .
            ( $this->request->getPost( 'suffix_and_ext' ) ? ' ' . $this->request->getPost( 'suffix_and_ext' ) : '' )
        ) ) );

        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Approved Seed Request',
            'details'      => "$staffFullName approved the seed request of \"$fullName\" (RSBSA: $rsbsa).",
            'users_tbl_id' => session( 'user_id' ),
        ] );

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Request approved.',
            'icon'  => 'success',
        ] );

        return redirect()->back()->with( 'message', 'Request approved and beneficiary recorded.' );
    }

    /**
     * Undoes the approval of a seed request by ID.
     *
     * @param int $id The ID of the seed request to undo approval for.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function undoApproved( $id )
    {
        $rsbsa     = $this->request->getPost( 'rsbsa' );
        $season    = $this->request->getPost( 'season' );
        $year      = $this->request->getPost( 'year' );
        $seedName  = $this->request->getPost( 'seed_name' );
        $seedClass = $this->request->getPost( 'seed_class' );

        $qrCode = "$season-$year-$seedName-$seedClass-$rsbsa";

        $requestModel     = new SeedRequestsModel();
        $beneficiaryModel = new BeneficiariesModel();
        $logsModel        = new LogsModel();

        $requestModel->update( $id, [ 
            'status'             => 'Pending',
            'date_time_approved' => null
        ] );

        $beneficiaryModel->where( 'qr_code', $qrCode )->delete();

        $formattedDate = getPhilippineTimeFormatted();

        /* Staff Fullname */
        $staffFullName = session( 'user_fullname' );

        $fullName = ucwords( strtolower( trim(
            $this->request->getPost( 'first_name' ) .
            ( $this->request->getPost( 'middle_name' ) ? ' ' . $this->request->getPost( 'middle_name' ) : '' ) .
            ' ' . $this->request->getPost( 'last_name' ) .
            ( $this->request->getPost( 'suffix_and_ext' ) ? ' ' . $this->request->getPost( 'suffix_and_ext' ) : '' )
        ) ) );

        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Undo Approval',
            'details'      => "$staffFullName reverted approval for \"$fullName\" (RSBSA: $rsbsa).",
            'users_tbl_id' => session( 'user_id' ),
        ] );

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Undo successful.',
            'icon'  => 'success',
        ] );

        return redirect()->back()->with( 'message', 'Approval undone and beneficiary removed.' );
    }

    /**
     * Rejects a seed request by ID.
     *
     * @param int $id The ID of the seed request to reject.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function reject( $id )
    {
        $formattedDate = getPhilippineTimeFormatted();
        $logsModel     = new LogsModel();
        $requestModel  = new SeedRequestsModel();

        $requestModel->update( $id, [ 
            'status'             => 'Rejected',
            'date_time_rejected' => $formattedDate
        ] );

        /* Staff Fullname */
        $staffFullName = session( 'user_fullname' );

        // Client Full Name
        $fullName = ucwords( strtolower( trim(
            $this->request->getPost( 'first_name' ) .
            ( $this->request->getPost( 'middle_name' ) ? ' ' . $this->request->getPost( 'middle_name' ) : '' ) .
            ' ' . $this->request->getPost( 'last_name' ) .
            ( $this->request->getPost( 'suffix_and_ext' ) ? ' ' . $this->request->getPost( 'suffix_and_ext' ) : '' )
        ) ) );

        $rsbsa = $this->request->getPost( 'rsbsa' );

        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Rejected Seed Request',
            'details'      => "$staffFullName rejected the seed request of \"$fullName\" (RSBSA: $rsbsa).",
            'users_tbl_id' => session( 'user_id' ),
        ] );

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Rejected.',
            'icon'  => 'success',
        ] );

        return redirect()->back()->with( 'message', 'Request rejected successfully.' );
    }

    /**
     * Undoes a rejected seed request by ID.
     *
     * @param int $id The ID of the seed request to undo rejection for.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function undoRejected( $id )
    {
        $formattedDate = getPhilippineTimeFormatted();
        $model         = new SeedRequestsModel();
        $logsModel     = new LogsModel();

        $model->update( $id, [ 
            'status'             => 'Pending',
            'date_time_rejected' => null,
        ] );

        /* Staff Fullname */
        $staffFullName = session( 'user_fullname' );

        // Client Full Name
        $fullName = ucwords( strtolower( trim(
            $this->request->getPost( 'first_name' ) .
            ( $this->request->getPost( 'middle_name' ) ? ' ' . $this->request->getPost( 'middle_name' ) : '' ) .
            ' ' . $this->request->getPost( 'last_name' ) .
            ( $this->request->getPost( 'suffix_and_ext' ) ? ' ' . $this->request->getPost( 'suffix_and_ext' ) : '' )
        ) ) );

        $rsbsa = $this->request->getPost( 'rsbsa' );

        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Undo Rejection',
            'details'      => "$staffFullName undid rejection of \"$fullName\" (RSBSA: $rsbsa).",
            'users_tbl_id' => session( 'user_id' ),
        ] );

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Undo successful.',
            'icon'  => 'success',
        ] );

        return redirect()->back()->with( 'message', 'Rejection has been undone.' );
    }

}
