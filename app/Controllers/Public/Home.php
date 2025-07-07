<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SeedRequestsModel;

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
        if ( !session()->get( "public_logged_in" ) ) {
            session()->set( "public_logged_in", false );
        }

        session()->set( "public_title", "home" );
        session()->set( "public_current_tab", "home" );

        $header = view( 'public/templates/header' );
        $body   = view( 'public/home' );
        $modals = view( 'public/dialog/loginModalDialog' );
        if ( session()->get( "public_logged_in" ) === true ) {
            $modals .= view( 'public/dialog/requestSeedModalDialog' );
        }
        $footer = view( 'public/templates/footer' );

        return $header . $body . $modals . $footer;
    }

    /**
     * Displays the seed request page.
     *
     * This method sets session variables for the public user and renders the seed request view.
     *
     * @return string The rendered view of the seed request page.
     */
    public function sentRequests()
    {
        if ( !session()->get( "public_logged_in" ) ) {
            return redirect()->to( base_url( '/public/home' ) );
        }
        // Ensure the user is not logged in
        if ( session()->get( "public_logged_in" ) === false ) {
            return redirect()->to( base_url( '/public/home' ) );
        }

        session()->set( "public_title", "sentRequests" );
        session()->set( "public_current_tab", "sentRequests" );

        $selectedSeason = session()->get( 'current_season_id' );
        $clientId       = session()->get( 'public_user_client_id' );

        $seedRequestModel = new SeedRequestsModel();

        $data[ 'seed_requests' ] = $seedRequestModel
            ->select( '
            seed_requests.*,
            inventory.seed_name,
            inventory.seed_class,
            cropping_season.season,
            cropping_season.year
        ' )
            ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
            ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id', 'left' )
            ->where( [ 
                // 'cropping_season.cropping_season_tbl_id' => $selectedSeason,     // e.g., 'Wet'
                'cropping_season.cropping_season_tbl_id' => $selectedSeason,     // e.g., 'Wet'
                'seed_requests.client_info_tbl_id'       => $clientId  // e.g., 12
            ] )
            ->orderBy( 'seed_requests.date_time_requested', 'DESC' )
            ->findAll();


        $header = view( 'public/templates/header' );
        $body   = view( 'public/sentRequests', $data );
        $modals = view( 'public/dialog/requestSeedModalDialog' );
        $modals .= view( 'public/dialog/editSentRequestsModalDialog' );
        $footer = view( 'public/templates/footer' );

        return $header . $body . $modals . $footer;
    }

    public function signUp()
    {

        session()->set( "public_title", "Sign Up" );
        session()->set( "public_current_tab", "Sign Up" );

        $header = view( 'public/templates/header' );
        $body   = view( 'public/signUp' );
        $modals = view( 'public/dialog/loginModalDialog' );
        $footer = view( 'public/templates/footer' );

        return $header . $body . $modals . $footer;
    }

    public function logout()
    {
        session()->remove( 'public_logged_in' );

        return redirect()->to( base_url( '/' ) );
    }
}
