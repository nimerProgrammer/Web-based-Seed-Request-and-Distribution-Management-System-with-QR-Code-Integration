<?php

/**
 * Get the current Philippine time formatted according to the specified format.
 *
 * @param string $format The format for the date and time.
 * @return string The formatted date and time in Philippine timezone.
 */
function getPhilippineTimeFormatted( $format = 'm-d-Y h:i:s A' )
{
    $philTime = new \DateTime( 'now', new \DateTimeZone( 'Asia/Manila' ) );
    return $philTime->format( $format );
}
