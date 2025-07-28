<?php

use App\Models\CroppingSeasonModel;

if ( !function_exists( 'checkCroppingSeason' ) ) {
    function checkCroppingSeason()
    {
        $model = new CroppingSeasonModel();
        $today = date( 'm-d-Y' );

        // 1. End current season if date_end is today or earlier
        $model->where( 'status', 'Current' )
            ->where( 'date_end <=', $today )
            ->set( [ 'status' => 'Ended' ] )
            ->update();

        // 2. Check if no season is currently active
        $hasCurrent = $model->where( 'status', 'Current' )->countAllResults();

        if ( $hasCurrent == 0 ) {
            // 3. Set a valid season to Current
            $model->where( 'date_start <=', $today )
                ->where( 'date_end >=', $today )
                ->where( 'status !=', 'Ended' )
                ->set( [ 'status' => 'Current' ] )
                ->update();
        }
    }
}
