<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\SeedRequestsModel;
use App\Models\BeneficiariesModel;
use App\Models\InventoryModel;
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
        $inventoryModel   = new InventoryModel();
        $logsModel        = new LogsModel();

        $qrCode = "$season {$year}_{$seedName}-{$seedClass}_{$rsbsa}";

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
        $refCode = 'REF-' . $dateCode . '-' . $randomCode;

        $requestModel->update( $id, [
            'status'             => 'Approved',
            'date_time_approved' => $formattedDate
        ] );

        
        $requests = $requestModel
            ->select( 'seed_requests.*, client_info.*, inventory.*, users.*' )
            ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
            ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
            ->join( 'users', 'users.users_tbl_id = client_info.users_tbl_id' )
            ->where( 'seed_requests.seed_requests_tbl_id', $id )
            ->first();

        $inventoryId = $requests[ 'inventory_tbl_id' ];
        $farmArea    = (float) $requests[ 'farm_area' ];
        $kg          = 0;

        if ( stripos( $requests[ 'seed_name' ], 'rice' ) !== false ) {
            if ( $farmArea <= 0.5 ) {
                $kg = 20; // minimum
            } else {
                // each full hectare step adds +10kg
                $kg = ( floor( $farmArea ) + 1 ) * 10;

                // cap at 50kg max
                if ( $kg > 50 ) {
                    $kg = 50;
                }
            }
        } else {


            if ( $farmArea <= 0.5 ) {
                $kg = 1; // minimum
            } else {
                // calculate steps
                $kg = floor( $farmArea ) + 1;

                // cap at 6kg (for 5.0 ha and above)
                if ( $kg > 6 ) {
                    $kg = 6;
                }
            }

        }

        $inventoryModel->set( 'requested', "IFNULL(requested, 0) + {$kg}", false )
            ->where( 'inventory_tbl_id', $inventoryId )
            ->update();


        $beneficiaryModel->insert( [
            'qr_code'              => $qrCode . '_' . $refCode,
            'status'               => 'For Receiving',
            'seed_requests_tbl_id' => $id,
            'kg'                   => $kg
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

        if ( $requests[ 'email' ] !== null ) {
            // Load your Email config
            // Get Email service with default config
            $email = \Config\Services::email();
            // Set recipient and subject
            // $email->setFrom( 'omas@oras-seed-request-distribution.com', 'LGU Oras' );
            $email->setTo( $requests[ 'email' ] );
            $email->setSubject( 'Your Seed Request Has Been Approved' );

            $message = <<<EOD
                <!DOCTYPE html>
                <html>
                <head>
                <meta charset="UTF-8">
                <title>Seed Request Approved</title>
                <style>
                    body { font-family: Arial, sans-serif; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
                    .bg {
                                    width: 100%;
                                    height: 100%;
                                    background-color: #e9e6e6ff; /* control gray */
                                    margin: 0;
                                    padding-top: 10px;
                                    padding-bottom: 10px;
                                  }
                    .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccc; }
                    .header { background-color: #4CAF50; color: #fff; padding: 10px; text-align: center; font-size: 18px; font-weight: bold; border-radius: 4px 4px 0 0; }
                    .content { padding: 20px; font-size: 14px; line-height: 1.5; }
                    .footer { font-size: 12px; color: #777; text-align: center; padding-top: 10px; }
                </style>
                </head>
                <body>
                <div class="bg">
                    <div class="container">
                        <div class="header">Seed Request Distribution</div>
                        <div class="content">
                            <p>Dear {$fullName},</p>
                            <p>Your seed request for <b>{$seedName} ({$seedClass})</b> has been <b>approved</b>.</p>
                            <p>Allocated Seeds: <b>{$kg} kg</b></p>
                            <p>Thank you for your inconvenience.</p>
                            <p>Sincerely,<br>OMAS Oras Team</p>
                        </div>
                        <div class="footer">
                            &copy; 2025 Seed Request and Distribution System. All rights reserved.
                        </div>
                    </div>
                    </div>
                </body>
                </html>
                EOD;

            $email->setMessage( $message );
            $email->setMailType( 'html' );
            $email->send();
            if ( !$email->send() ) {
                // Log debug info if email fails
                log_message( 'error', $email->printDebugger( [ 'headers', 'subject', 'body', 'SMTP' ] ) );
            }
        }


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
        $inventoryModel   = new InventoryModel();
        $logsModel        = new LogsModel();

        $requestModel->update( $id, [
            'status'             => 'Pending',
            'date_time_approved' => null
        ] );


        $requests = $requestModel
            ->select( 'seed_requests.*, client_info.*, inventory.*, beneficiaries.*' )
            ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
            ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
            ->join( 'beneficiaries', 'beneficiaries.seed_requests_tbl_id = seed_requests.seed_requests_tbl_id' )
            ->where( 'seed_requests.seed_requests_tbl_id', $id )
            ->first();

        $kg          = (float) $requests[ 'kg' ]; // from beneficiaries
        $inventoryId = $requests[ 'inventory_tbl_id' ];

        $inventoryModel->set( 'requested', "requested - {$kg}", false )
            ->where( 'inventory_tbl_id', $inventoryId )
            ->update();


        $beneficiaryModel->where( 'seed_requests_tbl_id', $id )->delete();

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
