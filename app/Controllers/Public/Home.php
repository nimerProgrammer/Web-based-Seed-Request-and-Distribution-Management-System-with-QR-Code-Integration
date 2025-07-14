<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SeedRequestsModel;
use App\Models\PostImageModel;
use App\Models\PostDescriptionModel;

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

        $postModel  = new PostDescriptionModel();
        $imageModel = new PostImageModel();

        $rawPosts = $postModel->orderBy( 'post_description_tbl_id', 'DESC' )->findAll();

        $posts = [];
        foreach ( $rawPosts as $post ) {
            $images = $imageModel
                ->where( 'post_description_tbl_id', $post[ 'post_description_tbl_id' ] )
                ->findAll();

            $posts[] = [ 
                'description' => $post[ 'description' ],
                'created_at'  => $post[ 'created_at' ],
                'images'      => $images
            ];
        }

        $data[ 'posts' ] = $posts;

        $header = view( 'public/templates/header' );
        $body   = view( 'public/home', $data );
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

    /**
     * Displays the profile page.
     *
     * This method sets session variables for the public user and renders the profile view.
     *
     * @return string The rendered view of the profile page.
     */
    public function profile()
    {
        if ( !session()->get( "public_logged_in" ) ) {
            return redirect()->to( base_url( '/public/home' ) );
        }
        // Ensure the user is not logged in
        if ( session()->get( "public_logged_in" ) === false ) {
            return redirect()->to( base_url( '/public/home' ) );
        }

        $usersModel = new UsersModel();

        $userID = session()->get( 'public_user_id' );

        $data[ 'users' ] = $usersModel
            ->select( 'users.*, client_info.*' )
            ->join( 'client_info', 'users.users_tbl_id = client_info.users_tbl_id' )
            ->where( 'users.users_tbl_id', $userID )
            ->first();

        $fullName = ucwords( strtolower( trim(
            $data[ 'users' ][ 'first_name' ] . ' ' .
            ( !empty( $data[ 'users' ][ 'middle_name' ] ) ? $data[ 'users' ][ 'middle_name' ] . ' ' : '' ) .
            $data[ 'users' ][ 'last_name' ]
        ) ) ) .
            ( !empty( $data[ 'users' ][ 'suffix_and_ext' ] ) ? ' ' . $data[ 'users' ][ 'suffix_and_ext' ] : '' );


        session()->set( 'public_user_fullname', $fullName );



        session()->set( "public_title", "profile" );
        session()->set( "public_current_tab", "profile" );

        $header = view( 'public/templates/header' );
        $body   = view( 'public/profile', $data );
        $modals = view( 'public/dialog/requestSeedModalDialog' );
        $modals .= view( 'public/dialog/profileModalDialog' );
        $footer = view( 'public/templates/footer' );

        return $header . $body . $modals . $footer;
    }

    /**
     * Displays the sign-up page.
     *
     * This method sets session variables for the public user and renders the sign-up view.
     *
     * @return string The rendered view of the sign-up page.
     */
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

    /**
     * Logs out the user by removing the session variable.
     *
     * This method is called when the user clicks the logout button.
     * It removes the 'public_logged_in' session variable and redirects to the home page.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        session()->remove( 'public_logged_in' );

        return redirect()->to( base_url( '/' ) );
    }
}
