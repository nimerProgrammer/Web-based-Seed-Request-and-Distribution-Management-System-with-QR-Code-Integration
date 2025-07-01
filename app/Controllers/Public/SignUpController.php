<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsersModel;
use App\Models\ClientInfoModel;


class SignUpController extends BaseController
{
    public function checker()
    {
        $table = $this->request->getPost( 'table' );
        $field = $this->request->getPost( 'field' );
        $value = $this->request->getPost( 'value' );

        if ( !$table || !$field || !$value ) {
            return $this->response->setJSON( [ 'exists' => false ] );
        }

        return $this->response->setJSON( [ 
            'exists' => isDuplicate( $table, $field, $value )
        ] );
    }

    public function submitSignUp()
    {
        $request     = service( 'request' );
        $clientModel = new ClientInfoModel();

        // Save to users table
        $userModel = new UsersModel();
        $userModel->insert( [ 
            'users_tbl_id'   => Null,
            'contact_no'     => $request->getPost( 'contact_no' ),
            'email'          => $request->getPost( 'email' ),
            'username'       => $request->getPost( 'username' ),
            'password'       => password_hash( $request->getPost( 'password' ), PASSWORD_DEFAULT ),
            'user_type'      => 'farmer',
            'account_status' => 'active',
        ] );

        $userID    = $userModel->getInsertID(); // Get ID of inserted user
        $birthdate = $request->getPost( 'birthdate' );
        $age       = date_diff( date_create( $birthdate ), date_create() )->y;

        $clientModel->insert( [ 
            'last_name'       => $this->request->getPost( 'last_name' ),
            'first_name'      => $this->request->getPost( 'first_name' ),
            'middle_name'     => $this->request->getPost( 'middle_name' ),
            'suffix_and_ext'  => $this->request->getPost( 'suffix' ),
            'gender'          => $this->request->getPost( 'gender' ),
            'age'             => $age,
            'b_date'          => $birthdate,
            'brgy'            => $this->request->getPost( 'barangay' ),
            'mun'             => $this->request->getPost( 'municipality' ),
            'prov'            => $this->request->getPost( 'province' ),
            'farm_area'       => $this->request->getPost( 'farm_area' ),
            'name_land_owner' => $this->request->getPost( 'land_owner' ),
            'rsbsa_ref_no'    => $this->request->getPost( 'rsbsa_no' ),
            'users_tbl_id'    => $userID,
        ] );

        session()->setFlashdata( 'swal', [ 
            'title'             => 'Success!',
            'text'              => 'Your account has been created successfully.',
            'icon'              => 'success',
            'confirmButtonText' => 'OK'
        ] );
        return redirect()->to( base_url( '/' ) );
    }



}
