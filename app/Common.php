<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

 
if (! function_exists('is_internet_available')) {
    /**
     * Checks if an internet connection is available by attempting to open a connection to Google.
     *
     * This function tries to open a socket connection to "www.google.com" on port 80.
     * If the connection is successful, it returns `true`, indicating internet access.
     * Otherwise, it returns `false`.
     *
     * @return bool Returns `true` if an internet connection is available, `false` otherwise.
     */
    function is_internet_available()
    {
        $connected = @fsockopen("www.google.com", 80);

        if ($connected) {
            fclose($connected);
            return true;
        }
        return false;
    }
}
