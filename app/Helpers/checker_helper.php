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

function isDuplicates( string $table, string $field, string $value, ?string $original = null ) : bool
{
    // if ( $table === 'client_info' ) {
    //     $model   = new ClientInfoModel();
    //     $builder = $model->where( $field, $value );

    //     if ( $original !== null ) {
    //         $builder->where( "$field !=", $original );
    //     }

    //     return $builder->first() !== null;
    // }

    if ( $table === 'users' ) {
        $model   = new UsersModel();
        $builder = $model->where( $field, $value );

        if ( $original !== null ) {
            $builder->where( "$field !=", $original );
        }

        return $builder->first() !== null;
    }
}


function isSeedRequestedByUser( $inventoryId, $userClientId )
{
    $model = new SeedRequestsModel();
    return $model
        ->where( 'inventory_tbl_id', $inventoryId )
        ->where( 'client_info_tbl_id', $userClientId )
        ->countAllResults() > 0;
}




