<?php

namespace App\Models;

use CodeIgniter\Model;

class LogsModel extends Model
{
    protected $table = 'logs'; // Fixed table name (snake_case, assuming DB table uses this)
    protected $primaryKey = 'logs_tbl_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [ 
        'timestamp',
        'action',
        'details',
        'users_tbl_id'
    ];
}
