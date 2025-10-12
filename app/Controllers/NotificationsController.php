<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\NotificationsModel;
class NotificationsController extends BaseController
{
    public function fetch()
    {
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
        // Return both in JSON format
        return $this->response->setJSON( [
            'success' => true,
        ] );
    }
}
