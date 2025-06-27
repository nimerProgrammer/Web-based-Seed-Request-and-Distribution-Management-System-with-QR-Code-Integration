<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BeneficiariesModel;
use App\Models\LogsModel;

class BeneficiariesController extends BaseController
{
    /**
     * Mark a beneficiary as "Received" and log the action.
     *
     * Updates the beneficiary's status and date-time received,
     * then inserts a log entry with user details from the session.
     *
     * @param int $id The ID of the beneficiary to mark as received.
     * @return ResponseInterface Redirects back to the beneficiaries page with a success or error message.
     */

    public function markReceived( int $id ) : ResponseInterface
    {
        $beneficiariesModel = new BeneficiariesModel();
        $logsModel          = new LogsModel();

        // Get Philippine time
        $formattedDate = getPhilippineTimeFormatted();

        // Update the status to "Received" and set the current date/time
        $beneficiariesModel->update( $id, [ 
            'status'             => 'Received',
            'date_time_received' => $formattedDate,
        ] );

        if ( $beneficiariesModel ) {
            // Get beneficiary info from the hidden form inputs
            $rsbsa     = $this->request->getPost( 'rsbsa_ref_no' );
            $firstName = $this->request->getPost( 'first_name' );
            $middle    = $this->request->getPost( 'middle_name' );
            $lastName  = $this->request->getPost( 'last_name' );
            $suffix    = $this->request->getPost( 'suffix_and_ext' );

            // Construct full name with optional middle name and suffix
            $fullName = ucwords( strtolower( trim(
                $firstName .
                ( $middle ? " $middle" : "" ) .
                " $lastName" .
                ( $suffix ? " $suffix" : "" )
            ) ) );

            /* Staff Fullname */
            $staffFullName = session( 'user_fullname' );

            // Prepare log data
            $logData = [ 
                'timestamp'    => $formattedDate,
                'action'       => 'Marked as Received',
                'details'      => $staffFullName . ' marked beneficiary "' . $fullName . '" (RSBSA: ' . $rsbsa . ') as received.',
                'users_tbl_id' => session( 'user_id' ),
            ];

            $logsModel->insert( $logData );

            session()->setFlashdata( 'swal', [ 
                'title'             => 'Success!',
                'text'              => 'Beneficiary marked as received.',
                'icon'              => 'success',
                'confirmButtonText' => 'OK'
            ] );
        } else {
            session()->setFlashdata( 'swal', [ 
                'title'             => 'Error!',
                'text'              => 'Failed to update beneficiary.',
                'icon'              => 'error',
                'confirmButtonText' => 'OK'
            ] );
        }

        return $this->response->redirect( base_url( '/admin/beneficiaries' ) );
    }

    /**
     * Undo the receive action for a beneficiary.
     *
     * @param int $id The ID of the beneficiary to undo receive.
     * @return ResponseInterface
     */
    public function undoReceive( int $id ) : ResponseInterface
    {
        $beneficiariesModel = new BeneficiariesModel();
        $logsModel          = new LogsModel();

        // Get Philippine time
        $formattedDate = getPhilippineTimeFormatted();

        // Get beneficiary info from form post
        $rsbsa     = $this->request->getPost( 'rsbsa_ref_no' );
        $firstName = $this->request->getPost( 'first_name' );
        $middle    = $this->request->getPost( 'middle_name' );
        $lastName  = $this->request->getPost( 'last_name' );
        $suffix    = $this->request->getPost( 'suffix_and_ext' );

        // Client full name
        $fullName = ucwords( strtolower( trim(
            $firstName .
            ( $middle ? " $middle" : "" ) .
            " $lastName" .
            ( $suffix ? " $suffix" : "" )
        ) ) );

        /* Staff Fullname */
        $staffFullName = session( 'user_fullname' );

        // Undo receive
        $beneficiariesModel->update( $id, [ 
            'status'             => 'For Receiving',
            'date_time_received' => null,
        ] );


        // Insert log
        $logData = [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Undo Received',
            'details'      => $staffFullName . ' reverted status of beneficiary "' . $fullName . '" (RSBSA: ' . $rsbsa . ') to "For Receiving".',
            'users_tbl_id' => session( 'user_id' ),
        ];
        $logsModel->insert( $logData );

        session()->setFlashdata( 'response', [ 
            'alert_type' => 'info',
            'message'    => 'Receive action undone.'
        ] );

        return $this->response->redirect( base_url( '/admin/beneficiaries' ) );
    }

}
