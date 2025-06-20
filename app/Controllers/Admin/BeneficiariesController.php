<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BeneficiariesModel;

class BeneficiariesController extends BaseController
{
    public function markReceived( int $id ) : ResponseInterface
    {
        $beneficiariesModel = new BeneficiariesModel();
        // Get Philippine time
        $philTime      = new \DateTime( 'now', new \DateTimeZone( 'Asia/Manila' ) );
        $formattedDate = $philTime->format( 'm-d-Y h:i A' );

        // Update the status to "Received" and set the current date/time
        $beneficiariesModel->update( $id, [ 
            'status'             => 'Received',
            'date_time_received' => $formattedDate,
        ] );

        if ( $beneficiariesModel ) {
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

        // Redirect back with ResponseInterface type
        return $this->response->redirect( base_url( '/admin/beneficiaries' ) );
    }

    public function undoReceive( int $id ) : ResponseInterface
    {
        $beneficiariesModel = new BeneficiariesModel();

        // Undo receive
        $beneficiariesModel->update( $id, [ 
            'status'             => 'For Receiving',
            'date_time_received' => null,
        ] );

        session()->setFlashdata( 'response', [ 
            'alert_type' => 'info',
            'message'    => 'Receive action undone.'
        ] );

        return $this->response->redirect( base_url( '/admin/beneficiaries' ) );
    }

}
