<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsersModel;
use App\Models\StaffInfoModel;
use App\Models\ClientInfoModel;
use App\Models\CroppingSeasonModel;
use App\Models\LogsModel;

class LoginController extends BaseController
{

    public function login() {}

    public function check_credentials()
    {
        $username = $this->request->getPost( 'username' );
        $password = $this->request->getPost( 'password' );

        $userModel           = new UsersModel();
        $croppingSeasonModel = new CroppingSeasonModel();
        $logsModel           = new LogsModel();

        $formattedDate = getPhilippineTimeFormatted();

        $user = $userModel
            ->where( 'username', $username )
            ->first();

        if ( !$user ) {
            $logData = [ 
                'timestamp'    => $formattedDate,
                'action'       => 'Login',
                'details'      => 'Login attempt failed — username not found: ' . $username,
                'users_tbl_id' => null,
            ];

            $logsModel->insert( $logData );

            return $this->response->setJSON( [ 'success' => false, 'error' => 'Username not found' ] );
        }

        if ( !password_verify( $password, $user[ 'password' ] ) ) {
            $logData = [ 
                'timestamp'    => $formattedDate,
                'action'       => 'Login',
                'details'      => 'Login attempt failed due to incorrect password for username: ' . $username . ', user type: ' . $user[ 'user_type' ],
                'users_tbl_id' => $user[ 'users_tbl_id' ],
            ];

            $logsModel->insert( $logData );
            return $this->response->setJSON( [ 'success' => false, 'error' => 'Wrong password' ] );
        }



        if ( $user[ 'user_type' ] === 'farmer' ) {
            $farmerModel = new ClientInfoModel();
            $farmer      = $farmerModel->where( 'users_tbl_id', $user[ 'users_tbl_id' ] )->first();

            if ( !$farmer ) {
                session()->setFlashdata( 'swal', [ 
                    'title'             => 'Error!',
                    'text'              => 'An error occurred fetching data. Please try again.',
                    'icon'              => 'error',
                    'confirmButtonText' => 'OK'
                ] );
                return redirect()->to( base_url( '/' ) );
            }

            // ✅ Set session with user + staff data
            session()->set( [ 
                'public_user_id'             => $user[ 'users_tbl_id' ],
                'public_user_email'          => $user[ 'email' ],
                'public_user_rsbsa_no'       => $farmer[ 'rsbsa_ref_no' ],
                'public_user_lastname'       => $farmer[ 'last_name' ],
                'public_user_firstname'      => $farmer[ 'first_name' ],
                'public_user_middlename'     => $farmer[ 'middle_name' ] ?? '',
                'public_user_suffix_and_ext' => $farmer[ 'suffix_and_ext' ] ?? '',
                'public_logged_in'           => true,
                'public_user_fullname'       => ucwords( strtolower( trim(
                    $farmer[ 'first_name' ] . ' ' .
                    ( !empty( $farmer[ 'middle_name' ] ) ? $farmer[ 'middle_name' ] . ' ' : '' ) .
                    $farmer[ 'last_name' ] .
                    ( !empty( $farmer[ 'suffix_and_ext' ] ) ? ' ' . $farmer[ 'suffix_and_ext' ] : '' )
                ) ) )
            ] );

            $logData = [ 
                'timestamp'    => $formattedDate,
                'action'       => 'Login',
                'details'      => 'User ' . esc( session( 'public_user_fullname' ) ) . ' logged in successfully.',
                'users_tbl_id' => session( 'public_user_id' ),
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
                'redirect_url' => base_url( 'public/home' )
            ] );
        }

        if ( $user[ 'user_type' ] === 'staff' || $user[ 'user_type' ] === 'admin' ) {

            $staffModel = new StaffInfoModel();
            $staff      = $staffModel->where( 'users_tbl_id', $user[ 'users_tbl_id' ] )->first();

            if ( !$staff ) {
                session()->setFlashdata( 'swal', [ 
                    'title'             => 'Error!',
                    'text'              => 'An error occurred fetching data. Please try again.',
                    'icon'              => 'error',
                    'confirmButtonText' => 'OK'
                ] );
                return redirect()->to( base_url( '/' ) );
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




    }

}
