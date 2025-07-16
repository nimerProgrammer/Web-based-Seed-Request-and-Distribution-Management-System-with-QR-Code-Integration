<?php

use App\Models\ClientInfoModel;
use App\Models\UsersModel;
use App\Models\SeedRequestsModel;

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

/**
 * Check if a value exists in a specific field in the users table, excluding the original value.
 *
 * @param string $table
 * @param string $field
 * @param string $value
 * @param string|null $original
 * @return bool
 */
function isDuplicates( string $table, string $field, string $value, ?string $original = null ) : bool
{
    if ( $table === 'users' ) {
        $model   = new UsersModel();
        $builder = $model->where( $field, $value );

        if ( $original !== null ) {
            $builder->where( "$field !=", $original );
        }

        return $builder->first() !== null;
    }
}

/**
 * Check if a seed request exists for a specific inventory ID and user client ID.
 *
 * @param int $inventoryId
 * @param int $userClientId
 * @return bool
 */
function isSeedRequestedByUser( $inventoryId, $userClientId )
{
    $model = new SeedRequestsModel();
    return $model
        ->where( 'inventory_tbl_id', $inventoryId )
        ->where( 'client_info_tbl_id', $userClientId )
        ->countAllResults() > 0;
}




