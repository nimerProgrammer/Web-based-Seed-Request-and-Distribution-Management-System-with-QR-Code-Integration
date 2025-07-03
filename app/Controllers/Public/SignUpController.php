<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsersModel;
use App\Models\ClientInfoModel;
use App\Models\LogsModel;
class SignUpController extends BaseController
{
    /**
     * Checks if a value already exists in a specified table and field.
     *
     * This method is used to check for duplicate entries in the database.
     * It returns a JSON response indicating whether the value exists or not.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
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

    /**
     * Handles the sign-up form submission.
     *
     * This method processes the sign-up form data, validates it, and saves the user information
     * to the database. It also logs the action and sets a success message in the session.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function submitSignUp()
    {
        $request     = service( 'request' );
        $clientModel = new ClientInfoModel();
        $userModel   = new UsersModel();
        $logsModel   = new LogsModel();

        $formattedDate = getPhilippineTimeFormatted();

        /* Save to users table */
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

        $firstName    = ucwords( strtolower( $this->request->getPost( 'first_name' ) ) );
        $middleName   = $this->request->getPost( 'middle_name' ) ? ucwords( strtolower( $this->request->getPost( 'middle_name' ) ) ) : null;
        $lastName     = ucwords( strtolower( $this->request->getPost( 'last_name' ) ) );
        $suffixAndExt = $this->request->getPost( 'suffix' ) ? ucwords( strtolower( $this->request->getPost( 'suffix' ) ) ) : null;

        $fullNameParts = [ $firstName ];

        if ( !empty( $middleName ) ) {
            $fullNameParts[] = $middleName;
        }

        $fullNameParts[] = $lastName;

        if ( !empty( $suffixAndExt ) ) {
            $fullNameParts[] = $suffixAndExt;
        }

        $fullName = implode( ' ', $fullNameParts );

        $clientModel->insert( [ 
            'last_name'       => $lastName,
            'first_name'      => $firstName,
            'middle_name'     => $middleName,
            'suffix_and_ext'  => $suffixAndExt,
            'gender'          => $this->request->getPost( 'gender' ),
            'age'             => $age,
            'b_date'          => $birthdate,
            'brgy'            => $this->request->getPost( 'barangay' ),
            'mun'             => $this->request->getPost( 'municipality' ),
            'prov'            => $this->request->getPost( 'province' ),
            'farm_area'       => $this->request->getPost( 'farm_area' ),
            'name_land_owner' => ucwords( strtolower( $this->request->getPost( 'land_owner' ) ) ),
            'rsbsa_ref_no'    => $this->request->getPost( 'rsbsa_no' ),
            'users_tbl_id'    => $userID,
        ] );

        $gender     = strtolower( $this->request->getPost( 'gender' ) ) === 'male';
        $genderType = $gender ? 'his' : 'her';

        /* Insert log entry */
        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Create Account',
            'details'      => 'Farmer "' . $fullName . '" signed up and created ' . $genderType . ' account.',
            'users_tbl_id' => $userID,
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
