<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{
    /**
     * Redirects to the home page.
     *
     * This method is called when the root URL is accessed.
     * It redirects to the public home page.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function index()
    {
        return redirect()->to( base_url( '/public/home' ) );
    }

    /**
     * Displays the home page.
     *
     * This method sets session variables for the public user and renders the home view.
     *
     * @return string The rendered view of the home page.
     */
    public function home()
    {
        // Ensure the user is not logged in
        if ( !session()->get( "public_user_id" ) ) {
            session()->set( "public_user_id", "not_logged_in" );
        }

        session()->set( "public_title", "home" );
        session()->set( "public_current_tab", "home" );

        $header = view( 'public/templates/header' );
        $body   = view( 'public/home' );
        // $modals = view('_admin/modals/profile_modal');
        $footer = view( 'public/templates/footer' );

        return $header . $body . $footer;
    }

    /**
     * Displays the seed request page.
     *
     * This method sets session variables for the public user and renders the seed request view.
     *
     * @return string The rendered view of the seed request page.
     */
    public function request_seed()
    {
        // Ensure the user is not logged in
        if ( !session()->get( "public_user_id" ) ) {
            session()->set( "public_user_id", "not_logged_in" );
        }

        session()->set( "public_title", "request_seed" );
        session()->set( "public_current_tab", "request_seed" );

        $header = view( 'public/templates/header' );
        $body   = view( 'public/request_seed' );
        // $modals = view('_admin/modals/profile_modal');
        $footer = view( 'public/templates/footer' );

        return $header . $body . $footer;
    }

    public function signUp()
    {

        session()->set( "public_title", "Sign Up" );
        session()->set( "public_current_tab", "Sign Up" );

        $header = view( 'public/templates/header' );
        $body   = view( 'public/signUp' );
        $footer = view( 'public/templates/footer' );

        return $header . $body . $footer;
    }
}
