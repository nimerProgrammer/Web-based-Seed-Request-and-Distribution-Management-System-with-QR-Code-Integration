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
            ->findAll();

        // Count unseen notifications
        $count = $model->where( 'status_view', 'unseen' )->countAllResults();

        foreach ( $notifications as &$notif ) {
            if ( $notif[ 'type' ] == 'new user' ) {
                $notif[ 'icon' ]  = 'bi bi-person-plus-fill';
                $notif[ 'color' ] = 'text-success';
            } elseif ( $notif[ 'type' ] == 'new request' ) {
                $notif[ 'icon' ]  = 'bi bi-exclamation-circle-fill';
                $notif[ 'color' ] = 'text-warning';
            } else {
                $notif[ 'icon' ]  = 'bi bi-info-circle-fill';
                $notif[ 'color' ] = 'text-primary';
            }
        }

        // Return both in JSON format
        return $this->response->setJSON( [
            'count'         => $count,
            'notifications' => $notifications
        ] );
    }

}
