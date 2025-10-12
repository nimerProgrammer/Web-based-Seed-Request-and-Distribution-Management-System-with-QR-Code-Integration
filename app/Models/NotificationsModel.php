<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationsModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'notifications_tbl_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'notifications_tbl_id',
        'content',
        'type',
        'status_view',
        'created_at',
        'updated_at',
        'users_tbl_id'

    ];


    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

}
