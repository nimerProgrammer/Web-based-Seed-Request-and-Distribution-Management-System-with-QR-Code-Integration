<?php

use App\Models\ClientInfoModel;
use App\Models\UsersModel;

/**
 * Check if a value exists in a specific field in the users table.
 *
 * @param string $field
 * @param string $value
 * @return bool
 */
function isDuplicate( string $table, string $field, string $value ) : bool
{
    if ( $table === 'client_info' ) {
        $model = new ClientInfoModel();
        return $model->where( $field, $value )->first() !== null;
    }

    if ( $table === 'users' ) {
        $model = new UsersModel();
        return $model->where( $field, $value )->first() !== null;
    }
}



