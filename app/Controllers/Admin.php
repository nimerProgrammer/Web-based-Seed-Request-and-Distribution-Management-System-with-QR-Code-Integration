<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UsersModel;
use App\Models\InventoryModel;
use App\Models\CroppingSeasonModel;
use App\Models\SeedRequestsModel;
use App\Models\ClientInfoModel;


class Admin extends BaseController
{
    public function index()
    {
        return session()->get( "user_id" ) ? redirect()->to( base_url( '/admin/dashboard' ) ) : redirect()->to( base_url( '/admin/login' ) );
    }

    public function login()
    {
        session()->set( "title", "Login" );
        session()->set( "current_tab", "login" );

        return view( 'admin/login' );
    }

    public function get_user_data()
    {
        $email = $this->request->getPost( 'email' );
        $password = $this->request->getPost( 'password' );

        $model = new UsersModel();
        $user = $model->where( 'email', $email )->first();

        if ( !$user ) {
            return $this->response->setJSON( [ 
                'success' => false,
                'error' => 'Email not found'
            ] );
        }

        if ( !password_verify( $password, $user[ 'password' ] ) ) {
            return $this->response->setJSON( [ 
                'success' => false,
                'error' => 'Wrong password'
            ] );
        }

        session()->set( [ 
            'user_id' => $user[ 'users_tbl_id' ],
            'user_email' => $user[ 'email' ],
            'logged_in' => true,
        ] );

        return $this->response->setJSON( [ 
            'success' => true,
            'redirect_url' => base_url( 'admin/dashboard' )
        ] );


    }

    public function get_user_data_by_id()
    {
        $user_id = $this->request->getPost( "user_id" );

        $User_Model = new UsersModel();

        $user_data = $User_Model->where( "users_tbl_id", $user_id )->findAll( 1 )[ 0 ];

        return json_encode( $user_data );
    }

    public function dashboard()
    {
        if ( !session()->get( "user_id" ) ) {
            session()->set( "redirect_after_login", base_url( uri_string() ) );

            $response = [ 
                "alert_type" => "danger",
                "message" => "You need to login first!"
            ];

            session()->setFlashdata( "response", $response );

            return redirect()->to( base_url( '/admin/login' ) );
        }

        session()->set( "title", "Dashboard" );
        session()->set( "current_tab", "dashboard" );

        $User_Model = new UsersModel();

        $data[ "user" ] = $User_Model->where( "users_tbl_id", session()->get( "user_id" ) )->findAll( 1 )[ 0 ];

        $header = view( 'admin/templates/header' );
        $body = view( 'admin/dashboard' );
        // $modals = view('_admin/modals/profile_modal');
        $footer = view( 'admin/templates/footer' );

        return $header . $body . $footer;
    }

    public function inventory()
    {
        if ( !session()->get( "user_id" ) ) {
            session()->set( "redirect_after_login", base_url( uri_string() ) );

            $response = [ 
                "alert_type" => "danger",
                "message" => "You need to login first!"
            ];

            session()->setFlashdata( "response", $response );

            return redirect()->to( base_url( '/admin/login' ) );
        }

        session()->set( "title", "Inventory" );
        session()->set( "current_tab", "inventory" );

        $inventoryModel = new InventoryModel();
        $cropping_seasonModel = new CroppingSeasonModel();

        $dataInventory[ 'inventory' ] = $inventoryModel
            ->select( 'inventory.*, cropping_season.season, cropping_season.year' )
            ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
            ->orderBy( 'inventory.seed_name', 'ASC' )
            ->findAll();

        $dataCroppingSeason[ 'cropping_season' ] = $cropping_seasonModel
            ->where( 'status', 'Current' )
            ->first();

        $header = view( 'admin/templates/header' );
        $body = view( 'admin/inventory', $dataInventory );
        $modals = view( 'admin/modals/inventory_seed_modal', $dataCroppingSeason );
        $footer = view( 'admin/templates/footer' );

        return $header . $body . $modals . $footer;
    }

    public function seedsRequests()
    {
        if ( !session()->get( "user_id" ) ) {
            session()->set( "redirect_after_login", base_url( uri_string() ) );

            $response = [ 
                "alert_type" => "danger",
                "message" => "You need to login first!"
            ];

            session()->setFlashdata( "response", $response );

            return redirect()->to( base_url( '/admin/login' ) );
        }

        session()->set( "title", "Seeds Requests" );
        session()->set( "current_tab", "seeds_requests" );

        $inventoryModel = new InventoryModel();
        $cropping_seasonModel = new CroppingSeasonModel();

        $dataInventory[ 'inventory' ] = $inventoryModel
            ->select( 'inventory.*, cropping_season.season, cropping_season.year' )
            ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
            ->orderBy( 'inventory.seed_name', 'ASC' ) // Order by seed_name alphabetically
            ->findAll();


        // ✅ Get seed requests where cropping_season is current, ordered by request date
        $seedRequestsModel = new SeedRequestsModel();

        $dataRequests[ 'seed_requests' ] = $seedRequestsModel
            ->select( [ 
                'seed_requests.*',
                'client_info.last_name',
                'client_info.first_name',
                'client_info.middle_name',
                'client_info.suffix_and_ext',
                'client_info.name_land_owner',
                'client_info.rsbsa_ref_no',
                'client_info.farm_area',
                'inventory.seed_name',
                'inventory.seed_class',
                'cropping_season.season',
                'cropping_season.year',
                'cropping_season.status AS cropping_status'
            ] )
            ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
            ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
            ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
            ->where( 'cropping_season.status', 'Current' )
            ->orderBy( 'seed_requests.date_time_requested', 'ASC' )
            ->findAll();

        // ✅ Merge all data and pass to view
        $data = array_merge( $dataInventory, $dataRequests );
        $header = view( 'admin/templates/header' );
        $body = view( 'admin/seedsRequests', $data );
        // $modals = view('_admin/modals/profile_modal');
        $footer = view( 'admin/templates/footer' );

        return $header . $body . $footer;
    }

    public function beneficiaries()
    {
        if ( !session()->get( "user_id" ) ) {
            session()->set( "redirect_after_login", base_url( uri_string() ) );

            $response = [ 
                "alert_type" => "danger",
                "message" => "You need to login first!"
            ];

            session()->setFlashdata( "response", $response );

            return redirect()->to( base_url( '/admin/login' ) );
        }

        session()->set( "title", "Beneficiaries" );
        session()->set( "current_tab", "beneficiaries" );

        $header = view( 'admin/templates/header' );
        $body = view( 'admin/beneficiaries' );
        // $modals = view('_admin/modals/profile_modal');
        $footer = view( 'admin/templates/footer' );

        return $header . $body . $footer;
    }

    public function reports()
    {
        if ( !session()->get( "user_id" ) ) {
            session()->set( "redirect_after_login", base_url( uri_string() ) );

            $response = [ 
                "alert_type" => "danger",
                "message" => "You need to login first!"
            ];

            session()->setFlashdata( "response", $response );

            return redirect()->to( base_url( '/admin/login' ) );
        }

        session()->set( "title", "Reports" );
        session()->set( "current_tab", "reports" );

        $header = view( 'admin/templates/header' );
        $body = view( 'admin/reports' );
        // $modals = view('_admin/modals/profile_modal');
        $footer = view( 'admin/templates/footer' );

        return $header . $body . $footer;
    }

    public function logs()
    {
        if ( !session()->get( "user_id" ) ) {
            session()->set( "redirect_after_login", base_url( uri_string() ) );

            $response = [ 
                "alert_type" => "danger",
                "message" => "You need to login first!"
            ];

            session()->setFlashdata( "response", $response );

            return redirect()->to( base_url( '/admin/login' ) );
        }

        session()->set( "title", "Logs" );
        session()->set( "current_tab", "logs" );

        $header = view( 'admin/templates/header' );
        $body = view( 'admin/logs' );
        // $modals = view('_admin/modals/profile_modal');
        $footer = view( 'admin/templates/footer' );

        return $header . $body . $footer;
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to( base_url( '/admin/login' ) );
    }

    // public function searching()
    // {
    //     $search = $this->request->getPost("search");

    //     $User_Model = new UsersModel();

    //     $user_data = $User_Model->like("email", $search)->findAll(5);

    //     return json_encode($user_data);

    // }
}
