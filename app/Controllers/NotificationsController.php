<?php

namespace App\Controllers;

use App\Controllers\BaseController;
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

        $id         = $this->request->getPost( 'id' );
        $request_id = $this->request->getPost( 'request_id' );

        if ( !$request_id ) {
            // Not found or already processed
            return $this->response->setJSON( [
                'error' => true,
            ] );
        }

        $requestModel->select( 'seed_requests.*' )
            ->where( 'seed_requests_tbl_id', $request_id )
            ->first();

        if ( !$requestModel ) {
            // Not found or already processed
            return $this->response->setJSON( [
                'error' => true,
            ] );
        }

        $notifModel->set( 'status_view', 'seen' )
            ->where( 'notifications_tbl_id', $id )
            ->update();

        $requestModel->set( 'status', 'Approved' )
            ->where( 'seed_requests_tbl_id', $request_id )
            ->update();

        if ( !$requestModel ) {
            return $this->response->setJSON( [
                'error' => true,
            ] );
        } else {
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

        $requestModel->select( 'seed_requests.*' )
            ->where( 'seed_requests_tbl_id', $request_id )
            ->first();

        if ( !$requestModel ) {
            // Not found or already processed
            return $this->response->setJSON( [
                'error' => true,
            ] );
        }

        $notifModel->set( 'status_view', 'seen' )
            ->where( 'notifications_tbl_id', $id )
            ->update();

        $requestModel->set( 'status', 'Rejected' )
            ->where( 'seed_requests_tbl_id', $request_id )
            ->update();

        if ( !$requestModel ) {
            return $this->response->setJSON( [
                'error' => true,
            ] );
        } else {
            return $this->response->setJSON( [
                'success' => true,
            ] );
        }
    }

}
