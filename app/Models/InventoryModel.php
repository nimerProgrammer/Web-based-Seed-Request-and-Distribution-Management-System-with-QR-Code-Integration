<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'inventory_tbl_id';

    protected $allowedFields = [ 
        'seed_name',
        'seed_class',
        'stock',
        'date_stored',
        'cropping_season_tbl_id'
    ];

    // protected $useTimestamps = false;
}
