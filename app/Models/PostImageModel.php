<?php

namespace App\Models;

use CodeIgniter\Model;

class PostImageModel extends Model
{
    protected $table = 'post_image';
    protected $primaryKey = 'post_image_tbl_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [ 
        'image_path',
        'post_description_tbl_id'
    ];
}
