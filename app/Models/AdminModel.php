<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'users_tbl_id';
    protected $allowedFields    = [
        'email', 
        'password'
    ];
}
