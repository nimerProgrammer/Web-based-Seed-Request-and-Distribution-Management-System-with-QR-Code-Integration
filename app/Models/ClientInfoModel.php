<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientInfoModel extends Model
{
    protected $table = 'client_info';
    protected $primaryKey = 'client_info_tbl_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [ 
        'last_name',
        'first_name',
        'middle_name',
        'suffix_and_ext',
        'gender',
        'age',
        'b_date',
        'st_pk_brgy',
        'mun',
        'prov',
        'farm_area',
        'name_land_owner',
        'rsbsa_ref_no',
        'users_tbl_id'
    ];
}
