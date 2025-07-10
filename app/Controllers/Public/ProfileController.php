<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsersModel;
use App\Models\ClientInfoModel;

class ProfileController extends BaseController
{

    public function checker()
    {
        $table    = $this->request->getPost( 'table' );
        $field    = $this->request->getPost( 'field' );
        $value    = $this->request->getPost( 'value' );
        $original = $this->request->getPost( 'original' );

        if ( !$table || !$field || !$value || !$original ) {
            return $this->response->setJSON( [ 'exists' => false ] );
        }

        return $this->response->setJSON( [ 
            'exists' => isDuplicates( $table, $field, $value, $original )
        ] );
    }

    public function updateFullname()
    {
        $lastName   = ucwords( strtolower( trim( $this->request->getPost( 'editLastName' ) ) ) );
        $firstName  = ucwords( strtolower( trim( $this->request->getPost( 'editFirstName' ) ) ) );
        $middleName = trim( $this->request->getPost( 'editMiddleName' ) );
        $middleName = $middleName ? ucwords( strtolower( $middleName ) ) : null;
        $extName    = trim( $this->request->getPost( 'editExtName' ) ) ?: null;

        $clientModel = new ClientInfoModel();

        // Get logged-in user ID from session
        $userID = session()->get( 'public_user_id' );

        // Perform the update
        $clientModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'first_name'     => $firstName,
                'last_name'      => $lastName,
                'middle_name'    => $middleName,
                'suffix_and_ext' => $extName,
            ] )
            ->update();

        // Optional: set a flash message
        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your name has been saved.',
            'icon'  => 'success',

        ] );


        return redirect()->to( base_url( 'public/profile' ) );
    }

    public function updateGender()
    {
        $gender = trim( $this->request->getPost( 'editGender' ) ) ?: null;

        $clientModel = new ClientInfoModel();

        $userID = session()->get( 'public_user_id' );

        $clientModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'gender' => $gender,
            ] )
            ->update();

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your gender has been saved.',
            'icon'  => 'success',

        ] );

        return redirect()->to( base_url( 'public/profile' ) );
    }

    public function updateBirthdate()
    {
        $birthdate = $this->request->getPost( 'editBirthdate' );
        $age       = date_diff( date_create( $birthdate ), date_create() )->y;

        $clientModel = new ClientInfoModel();

        $userID = session()->get( 'public_user_id' );

        $clientModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'age'    => $age,
                'b_date' => $birthdate,
            ] )
            ->update();

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your birthdate and age has been saved.',
            'icon'  => 'success',

        ] );

        return redirect()->to( base_url( 'public/profile' ) );
    }

    public function updateBarangay()
    {
        $editBarangay = trim( $this->request->getPost( 'editBarangay' ) ) ?: null;

        $clientModel = new ClientInfoModel();

        $userID = session()->get( 'public_user_id' );

        $clientModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'brgy' => $editBarangay,
            ] )
            ->update();

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your barangay has been saved.',
            'icon'  => 'success',

        ] );

        return redirect()->to( base_url( 'public/profile' ) );
    }

    public function updateFarmArea()
    {
        $editFarmArea = trim( $this->request->getPost( 'editFarmArea' ) ) ?: null;

        $clientModel = new ClientInfoModel();

        $userID = session()->get( 'public_user_id' );

        $clientModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'farm_area' => $editFarmArea,
            ] )
            ->update();

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your farm area has been saved.',
            'icon'  => 'success',

        ] );

        return redirect()->to( base_url( 'public/profile' ) );
    }

    public function updateLandOwner()
    {
        $editLandOwner = trim( $this->request->getPost( 'editLand_owner' ) ) ?: null;

        $clientModel = new ClientInfoModel();

        $userID = session()->get( 'public_user_id' );

        $clientModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'name_land_owner' => $editLandOwner,
            ] )
            ->update();

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your land owner name has been saved.',
            'icon'  => 'success',

        ] );

        return redirect()->to( base_url( 'public/profile' ) );
    }

    public function updateRSBSA()
    {
        $editRSBSA = trim( $this->request->getPost( 'editRSBSA' ) ) ?: null;

        $clientModel = new ClientInfoModel();

        $userID = session()->get( 'public_user_id' );

        $clientModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'rsbsa_ref_no' => $editRSBSA,
            ] )
            ->update();

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your RSBSA number has been saved.',
            'icon'  => 'success',

        ] );

        return redirect()->to( base_url( 'public/profile' ) );
    }

    public function updateContactNo()
    {
        $editContactNo = trim( $this->request->getPost( 'editContactNo' ) ) ?: null;

        $usersModel = new UsersModel();

        $userID = session()->get( 'public_user_id' );

        $usersModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'contact_no' => $editContactNo,
            ] )
            ->update();

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your contact number has been saved.',
            'icon'  => 'success',

        ] );

        return redirect()->to( base_url( 'public/profile' ) );
    }

    public function updateEmail()
    {
        $editEmail = trim( $this->request->getPost( 'editEmail' ) ) ?: null;

        $usersModel = new UsersModel();

        $userID = session()->get( 'public_user_id' );

        $usersModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'email' => $editEmail,
            ] )
            ->update();

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your email has been saved.',
            'icon'  => 'success',

        ] );

        return redirect()->to( base_url( 'public/profile' ) );
    }

    public function updateUsername()
    {
        $editUsername = strtolower( trim( $this->request->getPost( 'editUsername' ) ) ) ?: null;

        $usersModel = new UsersModel();

        $userID = session()->get( 'public_user_id' );

        $usersModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'username' => $editUsername,
            ] )
            ->update();

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your email has been saved.',
            'icon'  => 'success',

        ] );

        return redirect()->to( base_url( 'public/profile' ) );
    }

    public function checkCurrentPassword()
    {
        $currentPassword = $this->request->getPost( 'currentPassword' );
        $session         = session();
        $userId          = $session->get( 'public_user_id' ); // or however you're storing logged-in ID

        $model = new UsersModel();
        $user  = $model->find( $userId );

        if ( $user && password_verify( $currentPassword, $user[ 'password' ] ) ) {
            return $this->response->setJSON( [ 'valid' => true ] );
        }

        return $this->response->setJSON( [ 'valid' => false ] );
    }


    public function changePassword()
    {
        $session     = session();
        $userId      = $session->get( 'public_user_id' ); // adjust if needed
        $newPassword = $this->request->getPost( 'newPassword' );

        // Hash the new password
        $hashedPassword = password_hash( $newPassword, PASSWORD_DEFAULT );

        // Update the user's password
        $userModel = new UsersModel();
        $userModel->update( $userId, [ 
            'password' => $hashedPassword,
        ] );

        // Set SweetAlert flashdata
        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your password has been changed successfully.',
            'icon'  => 'success',
        ] );

        return redirect()->to( base_url( 'public/profile' ) );
    }


}
