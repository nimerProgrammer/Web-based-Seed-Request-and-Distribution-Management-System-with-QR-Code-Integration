<?php

namespace App\Models;

use CodeIgniter\Model;

class CroppingSeasonModel extends Model
{
    protected $table = 'cropping_season';
    protected $primaryKey = 'cropping_season_tbl_id';

    protected $allowedFields = [ 
        'season',
        'year',
        'date_start',
        'date_end',
        'status'
    ];

    // protected $useTimestamps = false;
}
