<?php

namespace App\Models;

use CodeIgniter\Model;

class PostDescriptionModel extends Model
{

    protected $table = 'post_description';
    protected $primaryKey = 'post_description_tbl_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [ 
        'description',
        'created_at',
        'updated_at',
        'users_tbl_id'
    ];

    public function getPostsWithImages()
    {
        return $this->select( 'post_description.*, post_image.image_path' )
            ->join( 'post_image', 'post_image.post_description_tbl_id = post_description.post_description_tbl_id', 'left' )
            ->orderBy( 'post_description.created_at', 'DESC' )
            ->findAll();
    }
}
