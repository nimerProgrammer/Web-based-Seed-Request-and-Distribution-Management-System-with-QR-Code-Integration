<?php

function getPhilippineTimeFormatted( $format = 'm-d-Y h:i:s A' )
{
    $philTime = new \DateTime( 'now', new \DateTimeZone( 'Asia/Manila' ) );
    return $philTime->format( $format );
}
