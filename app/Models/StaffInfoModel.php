<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffInfoModel extends Model
{
    protected $table = 'staff_info'; // Fixed table name (snake_case, assuming DB table uses this)
    protected $primaryKey = 'staff_info_tbl_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [ 
        'emp_id',
        'last_name',
        'first_name',
        'middle_name',
        'suffix_and_ext',
        'gender',
        'age',
        'b_date',
        'users_tbl_id'
    ];
}
