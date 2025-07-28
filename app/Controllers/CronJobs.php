<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class CronJobs extends BaseController
{
    public function checkSeason()
    {
        helper( 'croppingSeason' ); // Load the helper
        checkCroppingSeason();    // Call the function
        return $this->response->setJSON( [ 'status' => 'checked' ] );
    }
}
