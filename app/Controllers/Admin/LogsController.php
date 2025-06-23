<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LogsModel;

class LogsController extends BaseController
{
    /**
     * Displays the activity logs.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function clearLogs() : ResponseInterface
    {
        $logsModel = new LogsModel();
        $logsModel->truncate(); // Use deleteAll() if truncate causes issues with FK constraints

        session()->setFlashdata( 'swal', [ 
            'title'             => 'Logs Cleared!',
            'text'              => 'All activity logs have been successfully cleared.',
            'icon'              => 'success',
            'confirmButtonText' => 'OK'
        ] );

        // Redirect back to the logs page
        return redirect()->to( '/admin/logs' );
    }

}
