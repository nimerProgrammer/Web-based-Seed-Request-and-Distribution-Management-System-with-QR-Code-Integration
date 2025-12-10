<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BeneficiariesModel;
use App\Models\InventoryModel;
use App\Models\LogsModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\NotificationsModel;
use App\Models\SeedRequestsModel;

class NotificationsController extends BaseController
{
    public function fetch()
    {
        if ( !$this->request->isAJAX() ) {
            return view( 'errors/html/error_403' );
        }

        $model = new NotificationsModel();

        // Get unseen notifications with user info
        $notifications = $model->select( 'notifications.*, users.*,client_info.*' )
            ->join( 'users', 'users.users_tbl_id = notifications.users_tbl_id' )
            ->join( 'client_info', 'client_info.users_tbl_id = users.users_tbl_id' )
            ->where( 'notifications.status_view', 'unseen' )
            ->orderBy( 'notifications.created_at', 'DESC' )
            ->findAll();

        // Count unseen notifications
        $count = $model->where( 'status_view', 'unseen' )->countAllResults();

        foreach ( $notifications as &$notif ) {
            if ( $notif[ 'type' ] == 'new user' ) {
                $notif[ 'icon' ]  = 'bi bi-person-plus-fill fs-4';
                $notif[ 'color' ] = 'text-success';
            } elseif ( $notif[ 'type' ] == 'new request' ) {
                $notif[ 'icon' ]  = 'bi bi-send-fill fs-4';
                $notif[ 'color' ] = 'text-warning';
            } elseif ( $notif[ 'type' ] == 'cancel request' ) {
                $notif[ 'icon' ]  = 'bi bi-send-x-fill fs-4';
                $notif[ 'color' ] = 'text-danger';
            }
        }

        // Return both in JSON format
        return $this->response->setJSON( [
            'count'         => $count,
            'notifications' => $notifications
        ] );
    }

    public function seenAll()
    {
        if ( !$this->request->isAJAX() ) {
            return view( 'errors/html/error_403' );
        }

        $model = new NotificationsModel();

        $model->set( 'status_view', 'seen' )
            ->where( 'status_view', 'unseen' )
            ->update(); // updates all rows in the table
        // Return both in JSON format
        return $this->response->setJSON( [
            'success' => true,
        ] );
    }

    public function seen()
    {
        if ( !$this->request->isAJAX() ) {
            return view( 'errors/html/error_403' );
        }

        $id = $this->request->getPost( 'id' );

        $model = new NotificationsModel();

        $model->set( 'status_view', 'seen' )
            ->where( 'notifications_tbl_id', $id )
            ->update();
        // Return both in JSON format
        return $this->response->setJSON( [
            'success' => true,
        ] );
    }
    public function approve()
    {
        if ( !$this->request->isAJAX() ) {
            return view( 'errors/html/error_403' );
        }

        $notifModel   = new NotificationsModel();
        $requestModel = new SeedRequestsModel();

        $requestModel     = new SeedRequestsModel();
        $beneficiaryModel = new BeneficiariesModel();
        $inventoryModel   = new InventoryModel();
        $logsModel        = new LogsModel();

        $id         = $this->request->getPost( 'id' );
        $request_id = $this->request->getPost( 'request_id' );

        if ( !$request_id ) {
            // Not found or already processed
            return $this->response->setJSON( [
                'error' => true,
            ] );
        }

        $requests = $requestModel
            ->select( 'seed_requests.*, client_info.*, inventory.*, cropping_season.*, users.*' )
            ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
            ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
            ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
            ->join( 'users', 'users.users_tbl_id = client_info.users_tbl_id' )
            ->where( 'seed_requests.seed_requests_tbl_id', $request_id )
            ->first();




        // $requestModel->select( 'seed_requests.*' )
        //     ->where( 'seed_requests_tbl_id', $request_id )
        //     ->first();

        if ( !$requests ) {
            // Not found or already processed
            return $this->response->setJSON( [
                'error' => true,
            ] );
        }

        $fullName = ucwords( strtolower( trim(
            $requests[ 'first_name' ] .
            ( $requests[ 'middle_name' ] ? ' ' . $requests[ 'middle_name' ] : '' ) .
            ' ' . $requests[ 'last_name' ] .
            ( $requests[ 'suffix_and_ext' ] ? ' ' . $requests[ 'suffix_and_ext' ] : '' )
        ) ) );

        $season    = $requests[ 'season' ];
        $year      = $requests[ 'year' ];
        $seedName  = $requests[ 'seed_name' ];
        $seedClass = $requests[ 'seed_class' ];
        $rsbsa     = $requests[ 'rsbsa_ref_no' ];

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

        $requestModel->update( $request_id, [
            'status'             => 'Approved',
            'date_time_approved' => $formattedDate
        ] );


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
            'seed_requests_tbl_id' => $request_id,
            'kg'                   => $kg
        ] );

        /* Staff Fullname */
        $staffFullName = session( 'user_fullname' );



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


        $notifModel->set( 'status_view', 'seen' )
            ->where( 'notifications_tbl_id', $id )
            ->update();


        $requestModel->update( $request_id, [
            'status'             => 'Approved',
            'date_time_approved' => $formattedDate
        ] );

        if ( !$requestModel ) {
            return $this->response->setJSON( [
                'error' => true,
            ] );
        } else {
            session()->setFlashdata( 'swal', [
                'title' => 'Success!',
                'text'  => 'Request approved.',
                'icon'  => 'success',
            ] );

            return $this->response->setJSON( [
                'success' => true,
            ] );
        }

    }

    public function reject()
    {
        if ( !$this->request->isAJAX() ) {
            return view( 'errors/html/error_403' );
        }

        $notifModel   = new NotificationsModel();
        $requestModel = new SeedRequestsModel();

        $id         = $this->request->getPost( 'id' );
        $request_id = $this->request->getPost( 'request_id' );

        if ( !$request_id ) {
            // Not found or already processed
            return $this->response->setJSON( [
                'error' => true,
            ] );
        }



        $formattedDate = getPhilippineTimeFormatted();
        $logsModel     = new LogsModel();
        $requestModel  = new SeedRequestsModel();



        /* Staff Fullname */
        $staffFullName = session( 'user_fullname' );


        $requests = $requestModel
            ->select( 'seed_requests.*, client_info.*, inventory.*, cropping_season.*, users.*' )
            ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
            ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
            ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
            ->join( 'users', 'users.users_tbl_id = client_info.users_tbl_id' )
            ->where( 'seed_requests.seed_requests_tbl_id', $request_id )
            ->first();


        if ( !$requestModel ) {
            // Not found or already processed
            return $this->response->setJSON( [
                'error' => true,
            ] );
        }

        $rsbsa = $requests[ 'rsbsa_ref_no' ];

        $fullName = ucwords( strtolower( trim(
            $requests[ 'first_name' ] .
            ( $requests[ 'middle_name' ] ? ' ' . $requests[ 'middle_name' ] : '' ) .
            ' ' . $requests[ 'last_name' ] .
            ( $requests[ 'suffix_and_ext' ] ? ' ' . $requests[ 'suffix_and_ext' ] : '' )
        ) ) );





        $notifModel->set( 'status_view', 'seen' )
            ->where( 'notifications_tbl_id', $id )
            ->update();

        $requestModel->update( $request_id, [
            'status'             => 'Rejected',
            'date_time_rejected' => $formattedDate
        ] );

        if ( !$requestModel ) {
            return $this->response->setJSON( [
                'error' => true,
            ] );
        } else {

            $logsModel->insert( [
                'timestamp'    => $formattedDate,
                'action'       => 'Rejected Seed Request',
                'details'      => "$staffFullName rejected the seed request of \"$fullName\" (RSBSA: $rsbsa).",
                'users_tbl_id' => session( 'user_id' ),
            ] );

            session()->setFlashdata( 'swal', [
                'title' => 'Success!',
                'text'  => 'Request Rejected.',
                'icon'  => 'success',
            ] );

            return $this->response->setJSON( [
                'success' => true,
            ] );
        }
    }

}
