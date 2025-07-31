<?php

use App\Models\CroppingSeasonModel;

if ( !function_exists( 'checkCroppingSeason' ) ) {
    function checkCroppingSeason()
    {
        $model          = new CroppingSeasonModel();
        $today          = DateTime::createFromFormat( 'm-d-Y', date( 'm-d-Y' ) );
        $todayTimestamp = $today ? $today->getTimestamp() : false;

        if ( !$todayTimestamp ) {
            log_message( 'error', 'Invalid today date format.' );
            return;
        }

        $seasons = $model->findAll();

        foreach ( $seasons as $season ) {
            $startObj = DateTime::createFromFormat( 'm-d-Y', $season[ 'date_start' ] );
            $endObj   = DateTime::createFromFormat( 'm-d-Y', $season[ 'date_end' ] );

            if ( !$startObj || !$endObj ) {
                log_message( 'error', 'Invalid date format in season ID: ' . $season[ 'cropping_season_tbl_id' ] );
                continue;
            }

            $start = $startObj->getTimestamp();
            $end   = $endObj->getTimestamp();

            $currentStatus = $season[ 'status' ];
            $seasonId      = $season[ 'cropping_season_tbl_id' ];

            if ( $start <= $todayTimestamp && $end > $todayTimestamp ) {
                $model->update( $seasonId, [ 'status' => 'Current' ] );

                // Set session selected_cropping_season_id and name
                // $session    = Services::session();
                $seasonName = $season[ 'season' ] . ' ' . $season[ 'year' ];

                session()->set( [ 
                    'selected_cropping_season_id'   => $seasonId,
                    'selected_cropping_season_name' => $seasonName,
                ] );

            }

            if ( $currentStatus === 'Current' && $end <= $todayTimestamp ) {
                $model->update( $seasonId, [ 'status' => 'Ended' ] );
            }

            if ( $start >= $todayTimestamp ) {
                $model->update( $seasonId, [ 'status' => 'Ongoing' ] );
            }
        }
    }
}
