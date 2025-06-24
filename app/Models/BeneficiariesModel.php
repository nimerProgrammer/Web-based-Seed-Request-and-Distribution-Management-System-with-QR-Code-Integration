<?php

namespace App\Models;

use CodeIgniter\Model;

class BeneficiariesModel extends Model
{
    protected $table = 'beneficiaries';
    protected $primaryKey = 'beneficiaries_tbl_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [ 
        'qr_code',
        'ref_no',
        'date_time_received',
        'status',
        'seed_requests_tbl_id'
    ];

}
