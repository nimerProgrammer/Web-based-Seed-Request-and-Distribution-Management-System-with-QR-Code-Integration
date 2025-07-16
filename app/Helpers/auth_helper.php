<?php

/**
 * Redirects to the login page if the user is not logged in.
 *
 * @return \CodeIgniter\HTTP\RedirectResponse|null
 */
if ( !function_exists( 'ifLogin' ) ) {
    function ifLogin()
    {
        if ( !session()->get( "user_id" ) ) {

            return redirect()->to( base_url( '/admin/login' ) );
        }

        return null;
    }
}

