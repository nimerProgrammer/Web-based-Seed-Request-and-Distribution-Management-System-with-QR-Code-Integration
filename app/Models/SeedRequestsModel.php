<?php

namespace App\Models;

use CodeIgniter\Model;

class SeedRequestsModel extends Model
{
    protected $table = 'seed_requests'; // Fixed table name (snake_case, assuming DB table uses this)
    protected $primaryKey = 'seed_requests_tbl_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [ 
        'date_time_requested',
        'date_time_approved',
        'date_time_rejected',
        'status',
        'inventory_tbl_id',
        'client_info_tbl_id'
    ];

}
