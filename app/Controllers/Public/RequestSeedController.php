<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SeedRequestsModel;
use App\Models\LogsModel;
use App\Models\InventoryModel;


class RequestSeedController extends BaseController
{
    /**
     * Handles the submission of a seed request.
     *
     * This method processes the seed request submitted by the user,
     * logs the action, and sets a success message in the session.
     *
     * @return ResponseInterface Redirects back to the previous page with a success message.
     */
    public function requestSeedSubmit()
    {
        $seedID       = $this->request->getPost( 'seed_name' );
        $userID       = session()->get( 'public_user_id' );
        $userClientID = session()->get( 'public_user_client_id' );
        $rsbsa_no     = session()->get( 'public_user_rsbsa_no' );
        $fullname     = session()->get( 'public_user_fullname' );

        $model          = new SeedRequestsModel();
        $logsModel      = new LogsModel();
        $inventoryModel = new InventoryModel();

        $formattedDate = getPhilippineTimeFormatted();

        // ✅ Get the seed name from the inventory table
        $seed     = $inventoryModel->find( $seedID );
        $seedName = $seed ? $seed[ 'seed_name' ] : 'Unknown Seed';

        // Insert seed request
        $model->insert( [ 
            'date_time_requested' => $formattedDate,
            'date_time_approved'  => null,
            'date_time_rejected'  => null,
            'status'              => 'Pending',
            'inventory_tbl_id'    => $seedID,
            'client_info_tbl_id'  => $userClientID,
        ] );

        // Optional: Insert log
        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Seed Request Submitted',
            'details'      => 'Farmer ' . $fullname . ' (RSBSA No. ' . $rsbsa_no . ') submitted a seed request for ' . $seedName . '.',
            'users_tbl_id' => $userID,
        ] );

        session()->setFlashdata( 'swal', [ 
            'title'             => 'Success!',
            'text'              => 'Your seed request has been submitted successfully. Once approved, you can download the voucher with QR code from the Sent Requests page.',
            'icon'              => 'success',
            'confirmButtonText' => 'OK'
        ] );

        return redirect()->back();
    }

}
