<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LogsModel;
use App\Models\UsersModel;
use App\Models\StaffInfoModel;
class ProfileController extends BaseController
{
    /**
     * Handles duplicate value checking for profile fields.
     *
     * This method checks if a given value already exists in a specified table and field,
     * excluding the original value (used during updates).
     *
     * Accepts POST data:
     * - table: the table name to check
     * - field: the column name
     * - value: the new value to validate
     * - original: the original value to exclude
     *
     * Returns JSON:
     * { "exists": true|false }
     *
     * @return ResponseInterface
     */
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

    /**
     * Handles the update of the user's full name.
     *
     * Compares the submitted full name fields with existing values,
     * applies changes if needed, logs each field change, and shows a response.
     *
     * @return ResponseInterface
     */
    public function updateFullname()
    {
        $staffModel = new StaffInfoModel();
        $userID     = session()->get( 'user_id' );

        // Get current data
        $currentData = $staffModel->where( 'users_tbl_id', $userID )->first();

        // Get new values from form
        $newFirstName  = ucwords( strtolower( trim( $this->request->getPost( 'editFirstName' ) ) ) );
        $newLastName   = ucwords( strtolower( trim( $this->request->getPost( 'editLastName' ) ) ) );
        $newMiddleName = trim( $this->request->getPost( 'editMiddleName' ) );
        $newMiddleName = $newMiddleName ? ucwords( strtolower( $newMiddleName ) ) : null;
        $newExtName    = trim( $this->request->getPost( 'editExtName' ) ) ?: null;

        // Track changes with before and after
        $changes = [];

        if ( $newFirstName !== $currentData[ 'first_name' ] ) {
            $changes[] = "changed first name from '{$currentData[ 'first_name' ]}' to '$newFirstName'";
        }
        if ( $newLastName !== $currentData[ 'last_name' ] ) {
            $changes[] = "changed last name from '{$currentData[ 'last_name' ]}' to '$newLastName'";
        }
        if ( $newMiddleName !== $currentData[ 'middle_name' ] ) {
            $before    = $currentData[ 'middle_name' ] ?? '(none)';
            $after     = $newMiddleName ?? '(none)';
            $changes[] = "changed middle name from '$before' to '$after'";
        }
        if ( $newExtName !== $currentData[ 'suffix_and_ext' ] ) {
            $before    = $currentData[ 'suffix_and_ext' ] ?? '(none)';
            $after     = $newExtName ?? '(none)';
            $changes[] = "changed suffix/ext from '$before' to '$after'";
        }

        if ( !empty( $changes ) ) {
            // Update the values
            $staffModel
                ->where( 'users_tbl_id', $userID )
                ->set( [ 
                    'first_name'     => $newFirstName,
                    'last_name'      => $newLastName,
                    'middle_name'    => $newMiddleName,
                    'suffix_and_ext' => $newExtName,
                ] )
                ->update();

            // Format log details
            $changedText = implode( '; ', $changes );

            $formattedDate = getPhilippineTimeFormatted();

            $logsModel = new LogsModel();
            $logsModel->insert( [ 
                'timestamp'    => $formattedDate,
                'action'       => 'Update Fullname',
                'details'      => 'Admin ' . esc( session( 'user_fullname' ) ) . ' ' . $changedText . '.',
                'users_tbl_id' => $userID,
            ] );

            session()->setFlashdata( 'swal', [ 
                'title' => 'Success!',
                'text'  => 'Your name has been updated.',
                'icon'  => 'success',
            ] );
        } else {
            // No changes
            session()->setFlashdata( 'swal', [ 
                'title' => 'No Changes',
                'text'  => 'No changes were made to your name.',
                'icon'  => 'info',
            ] );
        }

        return redirect()->to( base_url( 'admin/profile' ) );
    }

    /**
     * Handles the update of the user's gender.
     *
     * Updates the gender field in the database, logs the action,
     * and sets a success message.
     *
     * @return ResponseInterface
     */
    public function updateGender()
    {
        $gender = trim( $this->request->getPost( 'editGender' ) ) ?: null;

        $staffModel = new StaffInfoModel();

        $userID = session()->get( 'user_id' );

        $staffModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'gender' => $gender,
            ] )
            ->update();

        $formattedDate = getPhilippineTimeFormatted();

        $logsModel = new LogsModel();
        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Update Gender',
            'details'      => 'Admin ' . esc( session( 'user_fullname' ) ) . ' updated their gender.',
            'users_tbl_id' => session( 'user_id' ),
        ] );

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your gender has been saved.',
            'icon'  => 'success',

        ] );

        return redirect()->to( base_url( 'admin/profile' ) );
    }

    /**
     * Handles the update of the user's birthdate and auto-calculates age.
     *
     * Updates the birthdate and computed age in the database, logs the action,
     * and sets a success message.
     *
     * @return ResponseInterface
     */
    public function updateBirthdate()
    {
        $birthdate = $this->request->getPost( 'editBirthdate' );
        $age       = date_diff( date_create( $birthdate ), date_create() )->y;

        $staffModel = new StaffInfoModel();

        $userID = session()->get( 'user_id' );

        $staffModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'age'    => $age,
                'b_date' => $birthdate,
            ] )
            ->update();

        $formattedDate = getPhilippineTimeFormatted();

        $logsModel = new LogsModel();
        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Update Birthdate',
            'details'      => 'Admin ' . esc( session( 'user_fullname' ) ) . ' updated their birthdate.',
            'users_tbl_id' => session( 'user_id' ),
        ] );

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your birthdate and age has been saved.',
            'icon'  => 'success',

        ] );

        return redirect()->to( base_url( 'admin/profile' ) );
    }

    /**
     * Handles the update of the user's contact number.
     *
     * Updates the contact number in the `users` table, logs the action,
     * and sets a success message.
     *
     * @return ResponseInterface
     */
    public function updateContactNo()
    {
        $editContactNo = trim( $this->request->getPost( 'editContactNo' ) ) ?: null;

        $usersModel = new UsersModel();

        $userID = session()->get( 'user_id' );

        $usersModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'contact_no' => $editContactNo,
            ] )
            ->update();

        $formattedDate = getPhilippineTimeFormatted();

        $logsModel = new LogsModel();
        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Update Contact Number',
            'details'      => 'Admin ' . esc( session( 'user_fullname' ) ) . ' updated their contact number.',
            'users_tbl_id' => session( 'user_id' ),
        ] );

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your contact number has been saved.',
            'icon'  => 'success',

        ] );

        return redirect()->to( base_url( 'admin/profile' ) );
    }

    /**
     * Handles the update of the user's email address.
     *
     * Updates the email in the `users` table, logs the action,
     * and sets a success message.
     *
     * @return ResponseInterface
     */
    public function updateEmail()
    {
        $editEmail = trim( $this->request->getPost( 'editEmail' ) ) ?: null;

        $usersModel = new UsersModel();

        $userID = session()->get( 'user_id' );

        $usersModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'email' => $editEmail,
            ] )
            ->update();

        $formattedDate = getPhilippineTimeFormatted();

        $logsModel = new LogsModel();
        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Update Email Address',
            'details'      => 'Admin ' . esc( session( 'user_fullname' ) ) . ' updated their email address.',
            'users_tbl_id' => session( 'user_id' ),
        ] );

        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your email has been saved.',
            'icon'  => 'success',

        ] );

        return redirect()->to( base_url( 'admin/profile' ) );
    }

    /**
     * Handles the update of the user's username.
     *
     * Updates the username in the `users` table, logs the action,
     * and sets a success message.
     *
     * @return ResponseInterface
     */
    public function updateUsername()
    {
        $editUsername = strtolower( trim( $this->request->getPost( 'editUsername' ) ) ) ?: null;

        $usersModel = new UsersModel();

        $userID = session()->get( 'user_id' );

        $usersModel
            ->where( 'users_tbl_id', $userID )
            ->set( [ 
                'username' => $editUsername,
            ] )
            ->update();

        $formattedDate = getPhilippineTimeFormatted();

        $logsModel = new LogsModel();
        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Update Username',
            'details'      => 'Admin ' . esc( session( 'user_fullname' ) ) . ' updated their username.',
            'users_tbl_id' => session( 'user_id' ),
        ] );


        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your email has been saved.',
            'icon'  => 'success',

        ] );

        return redirect()->to( base_url( 'admin/profile' ) );
    }

    /**
     * Verifies the current password entered by the user.
     *
     * Compares the submitted password against the hashed password in the database
     * and returns a JSON response indicating if it matched.
     *
     * Returns JSON:
     * { "valid": true|false }
     *
     * @return ResponseInterface
     */
    public function checkCurrentPassword()
    {
        $currentPassword = $this->request->getPost( 'currentPassword' );
        $session         = session();
        $userId          = $session->get( 'user_id' ); // or however you're storing logged-in ID

        $model = new UsersModel();
        $user  = $model->find( $userId );

        if ( $user && password_verify( $currentPassword, $user[ 'password' ] ) ) {
            return $this->response->setJSON( [ 'valid' => true ] );
        }

        return $this->response->setJSON( [ 'valid' => false ] );
    }

    /**
     * Handles the change of the user's password.
     *
     * Hashes and updates the new password in the `users` table,
     * logs the password change, and sets a success message.
     *
     * @return ResponseInterface
     */
    public function changePassword()
    {
        $session     = session();
        $userId      = $session->get( 'user_id' ); // adjust if needed
        $newPassword = $this->request->getPost( 'newPassword' );

        // Hash the new password
        $hashedPassword = password_hash( $newPassword, PASSWORD_DEFAULT );

        // Update the user's password
        $userModel = new UsersModel();
        $userModel->update( $userId, [ 
            'password' => $hashedPassword,
        ] );

        $formattedDate = getPhilippineTimeFormatted();

        $logsModel = new LogsModel();
        $logsModel->insert( [ 
            'timestamp'    => $formattedDate,
            'action'       => 'Change Password',
            'details'      => 'Admin ' . esc( session( 'user_fullname' ) ) . ' changed their password.',
            'users_tbl_id' => session( 'user_id' ),
        ] );

        // Set SweetAlert flashdata
        session()->setFlashdata( 'swal', [ 
            'title' => 'Success!',
            'text'  => 'Your password has been changed successfully.',
            'icon'  => 'success',
        ] );

        return redirect()->to( base_url( 'admin/profile' ) );
    }
}
