<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UsersModel;
use App\Models\InventoryModel;
use App\Models\CroppingSeasonModel;
use App\Models\SeedRequestsModel;
use App\Models\ClientInfoModel;
use App\Models\BeneficiariesModel;
use App\Models\StaffInfoModel;
use App\Models\LogsModel;
use App\Models\PostDescriptionModel;
use App\Models\PostImageModel;


class Admin extends BaseController
{
    /**
     * Redirects to the appropriate page based on user session.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function index()
    {
        return session()->get( "user_id" ) ? redirect()->to( base_url( '/admin/dashboard' ) ) : redirect()->to( base_url( '/admin/login' ) );
    }

    /**
     * Displays the login page.
     *
     * @return string
     */
    public function login()
    {
        session()->set( "title", "Login" );
        session()->set( "current_tab", "login" );

        return view( 'admin/login' );
    }

    /**
     * Handles user login and retrieves user data.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function get_user_data()
    {
        $email    = $this->request->getPost( 'email' );
        $password = $this->request->getPost( 'password' );

        $model               = new UsersModel();
        $logsModel           = new LogsModel();
        $croppingSeasonModel = new CroppingSeasonModel();

        $formattedDate = getPhilippineTimeFormatted();

        $user = $model->where( 'email', $email )
            ->where( 'user_type', 'admin' )
            ->first();

        if ( !$user ) {

            $logData = [ 
                'timestamp'    => $formattedDate,
                'action'       => 'Login',
                'details'      => 'Login attempt failed — email not found: ' . $email,
                'users_tbl_id' => null,
            ];

            $logsModel->insert( $logData );

            return $this->response->setJSON( [ 
                'success' => false,
                'error'   => 'Email not found'
            ] );
        }

        if ( !password_verify( $password, $user[ 'password' ] ) ) {

            $logData = [ 
                'timestamp'    => $formattedDate,
                'action'       => 'Login',
                'details'      => 'Login attempt failed due to incorrect password for email: ' . $email,
                'users_tbl_id' => $user[ 'users_tbl_id' ],
            ];

            $logsModel->insert( $logData );

            return $this->response->setJSON( [ 
                'success' => false,
                'error'   => 'Wrong password'
            ] );
        }

        // ✅ Fetch staff info using StaffModel
        $staffModel = new StaffInfoModel();
        $staff      = $staffModel->where( 'users_tbl_id', $user[ 'users_tbl_id' ] )->first();

        if ( !$staff ) {
            return $this->response->setJSON( [ 
                'success' => false,
                'error'   => 'Staff info not found'
            ] );
        }

        // ✅ Set session with user + staff data
        session()->set( [ 
            'user_id'             => $user[ 'users_tbl_id' ],
            'user_email'          => $user[ 'email' ],
            'emp_id'              => $staff[ 'emp_id' ],
            'user_lastname'       => $staff[ 'last_name' ],
            'user_firstname'      => $staff[ 'first_name' ],
            'user_middlename'     => $staff[ 'middle_name' ] ?? '',
            'user_suffix_and_ext' => $staff[ 'suffix_and_ext' ] ?? '',
            'logged_in'           => true,
            'user_fullname'       => ucwords( strtolower( trim(
                $staff[ 'first_name' ] . ' ' .
                ( !empty( $staff[ 'middle_name' ] ) ? $staff[ 'middle_name' ] . ' ' : '' ) .
                $staff[ 'last_name' ] .
                ( !empty( $staff[ 'suffix_and_ext' ] ) ? ' ' . $staff[ 'suffix_and_ext' ] : '' )
            ) ) )
        ] );

        $logData = [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Login',
            'details'      => 'User ' . esc( session( 'user_fullname' ) ) . ' logged in successfully.',
            'users_tbl_id' => session( 'user_id' ),
        ];

        $logsModel->insert( $logData );

        $currentSeason = $croppingSeasonModel
            ->select( 'cropping_season_tbl_id, season, year' )
            ->where( 'status', 'Current' )
            ->first(); // Use `first()` since you expect only one

        if ( $currentSeason ) {
            session()->set( [ 
                'current_season_id'   => $currentSeason[ 'cropping_season_tbl_id' ],
                'current_season_name' => $currentSeason[ 'season' ] . ' ' . $currentSeason[ 'year' ]
            ] );
        }

        return $this->response->setJSON( [ 
            'success'      => true,
            'redirect_url' => base_url( 'admin/dashboard' )
        ] );


    }

    /**
     * Displays the admin dashboard.
     *
     * @return string
     */
    public function dashboard()
    {
        $redirect = ifLogin();
        if ( $redirect ) {
            return $redirect;
        }

        session()->set( "title", "Dashboard" );
        session()->set( "current_tab", "dashboard" );

        $seasonModel = new CroppingSeasonModel();

        $data[ 'seasons' ]       = $seasonModel->orderBy( 'year', 'DESC' )->findAll();
        $currentSeason           = $seasonModel->where( 'status', 'Current' )->first();
        $data[ 'currentSeason' ] = $currentSeason;

        $header = view( 'admin/templates/header' );
        $body   = view( 'admin/dashboard', $data );
        // $modals = view('_admin/modals/profile_modal');
        $footer = view( 'admin/templates/footer' );

        return $header . $body . $footer;
    }

    /**
     * Displays the admin dashboard.
     *
     * @return string
     */
    public function publicPage()
    {
        $redirect = ifLogin();
        if ( $redirect ) {
            return $redirect;
        }

        session()->set( "title", "PublicPage" );
        session()->set( "current_tab", "publicPage" );

        $postModel  = new PostDescriptionModel();
        $imageModel = new PostImageModel();

        $rawPosts = $postModel->orderBy( 'post_description_tbl_id', 'DESC' )->findAll();

        $posts = [];
        foreach ( $rawPosts as $post ) {
            $images = $imageModel
                ->where( 'post_description_tbl_id', $post[ 'post_description_tbl_id' ] )
                ->findAll();

            $posts[] = [ 
                'post_description_tbl_id' => $post[ 'post_description_tbl_id' ],
                'description'             => $post[ 'description' ],
                'created_at'              => $post[ 'created_at' ],
                'images'                  => $images
            ];
        }

        $data[ 'posts' ] = $posts;

        $view   = view( 'admin/publicPage', $data );
        $modals = view( 'admin/modals/publicPageModal' );


        return $view . $modals;
    }

    /**
     * Displays the inventory page.
     *
     * @return string
     */
    public function inventory()
    {
        $redirect = ifLogin();
        if ( $redirect ) {
            return $redirect;
        }

        session()->set( "title", "Inventory" );
        session()->set( "current_tab", "inventory" );

        $inventoryModel       = new InventoryModel();
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
        $body   = view( 'admin/inventory', $dataInventory );
        $modals = view( 'admin/modals/inventory_seed_modal', $dataCroppingSeason );
        $footer = view( 'admin/templates/footer' );

        return $header . $body . $modals . $footer;
    }

    /**
     * Displays the seeds requests page.
     *
     * @return string
     */
    public function seedsRequests()
    {
        $redirect = ifLogin();
        if ( $redirect ) {
            return $redirect;
        }


        if ( !session()->has( 'selected_seedrequests_barangay_name' ) ) {
            session()->set( 'selected_seedrequests_barangay_name', 'Agsam' );
        }

        $brgy = session()->get( 'selected_seedrequests_barangay_name' );

        session()->set( "title", "Seeds Requests" );
        session()->set( "current_tab", "seeds_requests" );

        $inventoryModel     = new InventoryModel();
        $beneficiariesModel = new BeneficiariesModel();
        $seedRequestsModel  = new SeedRequestsModel();

        $dataInventory[ 'inventory' ] = $inventoryModel
            ->select( 'inventory.*, cropping_season.season, cropping_season.year' )
            ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
            ->orderBy( 'inventory.seed_name', 'ASC' )
            ->findAll();

        $dataRequests[ 'seed_requests' ] = $seedRequestsModel
            ->select( [ 
                'seed_requests.*',
                'client_info.last_name',
                'client_info.first_name',
                'client_info.middle_name',
                'client_info.suffix_and_ext',
                'client_info.brgy',
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
            ->where( 'client_info.brgy', $brgy )
            ->where( 'cropping_season.status', 'Current' )
            ->orderBy( 'seed_requests.date_time_requested', 'ASC' )
            ->findAll();

        // ✅ Add beneficiary_status to each request
        foreach ( $dataRequests[ 'seed_requests' ] as &$request ) {
            $season    = $request[ 'season' ];
            $year      = $request[ 'year' ];
            $seedName  = $request[ 'seed_name' ];
            $seedClass = $request[ 'seed_class' ];
            $rsbsa     = $request[ 'rsbsa_ref_no' ];
            $requestId = $request[ 'seed_requests_tbl_id' ];

            $qrCode = "{$season}-{$year}-{$seedName}-{$seedClass}-{$rsbsa}";

            $beneficiary = $beneficiariesModel
                ->where( 'seed_requests_tbl_id', $requestId )
                ->where( 'qr_code', $qrCode )
                ->first();

            $request[ 'beneficiary_status' ] = $beneficiary[ 'status' ] ?? null;
        }

        unset( $request ); // clear reference

        // ✅ Merge all data and pass to view
        $data   = array_merge( $dataInventory, $dataRequests );
        $header = view( 'admin/templates/header' );
        $body   = view( 'admin/seedsRequests', $data );
        // $modals = view('_admin/modals/profile_modal');
        $footer = view( 'admin/templates/footer' );

        return $header . $body . $footer;
    }

    /**
     * Displays the beneficiaries page.
     *
     * @return string
     */
    public function beneficiaries()
    {
        $redirect = ifLogin();
        if ( $redirect ) {
            return $redirect;
        }

        session()->set( "title", "Beneficiaries" );
        session()->set( "current_tab", "beneficiaries" );

        if ( !session()->has( 'selected_beneficiaries_barangay_name' ) ) {
            session()->set( 'selected_beneficiaries_barangay_name', 'Agsam' );
        }

        $brgy = session()->get( 'selected_beneficiaries_barangay_name' );

        $inventoryModel     = new InventoryModel();
        $beneficiariesModel = new BeneficiariesModel();
        $seedRequestsModel  = new SeedRequestsModel();
        $usersModel         = new UsersModel();

        $dataBeneficiaries[ 'beneficiaries' ] = $beneficiariesModel
            ->select( [ 
                'beneficiaries.*',
                'client_info.rsbsa_ref_no',
                'client_info.last_name',
                'client_info.first_name',
                'client_info.middle_name',
                'client_info.suffix_and_ext',
                'client_info.brgy',
                'client_info.mun',
                'client_info.prov',
                'client_info.b_date',
                'client_info.gender',
                'client_info.farm_area',
                'client_info.name_land_owner',
                'users.contact_no',
                'inventory.inventory_tbl_id',
                'inventory.seed_name',
                'inventory.seed_class',
                'cropping_season.season',
                'cropping_season.year'
            ] )
            ->join( 'seed_requests', 'seed_requests.seed_requests_tbl_id = beneficiaries.seed_requests_tbl_id' )
            ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
            ->join( 'users', 'users.users_tbl_id = client_info.users_tbl_id' ) // ✅ Join to get contact_no
            ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
            ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
            ->where( 'cropping_season.status', 'Current' )
            ->where( 'client_info.brgy', $brgy )
            ->orderBy( 'beneficiaries.date_time_received', 'DESC' )
            ->orderBy( 'client_info.last_name', 'ASC' )
            ->findAll();


        $dataInventory[ 'inventory' ] = $inventoryModel
            ->select( 'inventory.*, cropping_season.season, cropping_season.year' )
            ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
            ->orderBy( 'inventory.seed_name', 'ASC' )
            ->findAll();

        $data   = array_merge( $dataInventory, $dataBeneficiaries );
        $header = view( 'admin/templates/header' );
        $body   = view( 'admin/beneficiaries', $data );
        // $modals = view('_admin/modals/profile_modal');
        $footer = view( 'admin/templates/footer' );

        return $header . $body . $footer;
    }

    /**
     * Displays the reports page.
     *
     * @return string
     */
    public function reports()
    {
        $redirect = ifLogin();
        if ( $redirect ) {
            return $redirect;
        }

        session()->set( "title", "Reports" );
        session()->set( "current_tab", "reports" );

        if ( !session()->has( 'selected_report_barangay_name' ) ) {
            session()->set( 'selected_report_barangay_name', 'Agsam' );
        }

        $brgy = session()->get( 'selected_report_barangay_name' );


        if ( !session()->has( 'selected_cropping_season_id' ) ) {
            // Check if fallback cropping_season_id is available
            if ( session()->has( 'current_season_id' ) && session()->has( 'current_season_name' ) ) {
                session()->set( [ 
                    'selected_cropping_season_id'   => session()->get( 'current_season_id' ),
                    'selected_cropping_season_name' => session()->get( 'current_season_name' )
                ] );
            }
        }

        $selectedSeasonId = session()->get( 'selected_cropping_season_id' );

        $inventoryModel      = new InventoryModel();
        $seedRequestsModel   = new SeedRequestsModel();
        $beneficiariesModel  = new BeneficiariesModel();
        $croppingSeasonModel = new CroppingSeasonModel();

        $dataSeasons[ 'cropping_seasons' ] = $croppingSeasonModel->findAll();

        $dataInventory[ 'inventory' ] = $inventoryModel
            ->select( 'inventory.*, cropping_season.season, cropping_season.year' )
            ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
            ->orderBy( 'inventory.seed_name', 'ASC' )
            ->findAll();

        if ( !session()->has( 'selected_list' ) ) {
            session()->set( 'selected_list', 'seedrequests' );
        }
        if ( session()->get( 'selected_list' ) === 'seedrequests' ) {

            $dataRequests[ 'seed_requests' ] = $seedRequestsModel
                ->select( [ 
                    'seed_requests.*',
                    'client_info.last_name',
                    'client_info.first_name',
                    'client_info.middle_name',
                    'client_info.suffix_and_ext',
                    'client_info.brgy',
                    'client_info.name_land_owner',
                    'client_info.rsbsa_ref_no',
                    'client_info.farm_area',
                    'inventory.seed_name',
                    'inventory.seed_class',
                    'cropping_season.season',
                    'cropping_season.year',
                    'cropping_season.cropping_season_tbl_id'
                ] )
                ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
                ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
                ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
                ->where( 'cropping_season.cropping_season_tbl_id', $selectedSeasonId )
                ->where( 'client_info.brgy', $brgy )
                ->orderBy( 'client_info.brgy', 'ASC' )
                ->orderBy( 'client_info.last_name', 'ASC' )
                ->findAll();
        }

        if ( session()->get( 'selected_list' ) === 'beneficiaries' ) {
            $dataBeneficiaries[ 'beneficiaries' ] = $beneficiariesModel
                ->select( [ 
                    'beneficiaries.*',
                    'client_info.rsbsa_ref_no',
                    'client_info.last_name',
                    'client_info.first_name',
                    'client_info.middle_name',
                    'client_info.suffix_and_ext',
                    'client_info.brgy',
                    'client_info.mun',
                    'client_info.prov',
                    'client_info.b_date',
                    'client_info.gender',
                    'client_info.farm_area',
                    'client_info.name_land_owner',
                    'users.contact_no',
                    'inventory.inventory_tbl_id',
                    'inventory.seed_name',
                    'inventory.seed_class',
                    'cropping_season.season',
                    'cropping_season.year'
                ] )
                ->join( 'seed_requests', 'seed_requests.seed_requests_tbl_id = beneficiaries.seed_requests_tbl_id' )
                ->join( 'client_info', 'client_info.client_info_tbl_id = seed_requests.client_info_tbl_id' )
                ->join( 'users', 'users.users_tbl_id = client_info.users_tbl_id' )
                ->join( 'inventory', 'inventory.inventory_tbl_id = seed_requests.inventory_tbl_id' )
                ->join( 'cropping_season', 'cropping_season.cropping_season_tbl_id = inventory.cropping_season_tbl_id' )
                ->where( 'cropping_season.cropping_season_tbl_id', $selectedSeasonId )
                ->where( 'client_info.brgy', $brgy )
                ->orderBy( 'client_info.last_name', 'ASC' )
                ->findAll();
        }
        $data = array_merge(
            $dataInventory,
            $dataSeasons,
            session()->get( 'selected_list' ) === 'beneficiaries' ? $dataBeneficiaries : $dataRequests
        );

        $header = view( 'admin/templates/header' );
        $body   = view( 'admin/reports', $data );
        $footer = view( 'admin/templates/footer' );

        return $header . $body . $footer;
    }

    /**
     * Displays the logs page.
     *
     * @return string
     */
    public function logs()
    {
        $redirect = ifLogin();
        if ( $redirect ) {
            return $redirect;
        }

        session()->set( "title", "Logs" );
        session()->set( "current_tab", "logs" );

        $logsModel = new LogsModel();

        // Fetch all logs, optionally ordered by latest

        $data[ 'logs' ] = $logsModel
            ->select( 'logs.*, users.user_type' )
            ->join( 'users', 'users.users_tbl_id = logs.users_tbl_id', 'left' ) // ← Use 'left' join
            ->orderBy( 'logs.logs_tbl_id', 'DESC' )
            ->findAll();

        $header = view( 'admin/templates/header' );
        $body   = view( 'admin/logs', $data );
        // $modals = view('_admin/modals/profile_modal');
        $footer = view( 'admin/templates/footer' );

        return $header . $body . $footer;
    }

    /**
     * Handles user logout and redirects to the login page.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        // session()->remove( 'user_id' );
        session()->destroy();

        return redirect()->to( base_url( '/admin/login' ) );
    }


}
