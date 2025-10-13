<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ClientInfoModel;
use App\Models\UsersModel;

class ForgotPassword extends BaseController
{
    public function forgotPassword()
    {
        $emailAcc = $this->request->getPost( 'email' );

        $model      = new UsersModel();
        $staff_info = new ClientInfoModel();

        $user = $model->where( 'email', $emailAcc )
            ->where( 'user_type', 'farmer' )
            ->first();

        if ( !$user ) {
            return $this->response->setJSON( [
                'error' => true
            ] );
        }
        $id       = $user[ 'users_tbl_id' ];
        $info     = $staff_info->where( 'users_tbl_id', $id )->first();
        $fullname = ucwords( strtolower( trim(
            $info[ 'first_name' ] . ' ' .
            ( !empty( $info[ 'middle_name' ] ) ? $info[ 'middle_name' ] . ' ' : '' ) .
            $info[ 'last_name' ] .
            ( !empty( $info[ 'suffix_and_ext' ] ) ? ' ' . $info[ 'suffix_and_ext' ] : '' )
        ) ) );


        $secretKey = 'SEEDREQUEST2025'; // any random string as your secret
        $expiresAt = time() + ( 30 * 60 ); // expires in 15 mins

        $rawToken = $emailAcc . '|' . $expiresAt . '|' . $secretKey;
        $token    = base64_encode( $rawToken );

        $resetLink = base_url( "public/reset-password?token={$token}" );

        $email = \Config\Services::email();
        // Set recipient and subject
        // $email->setFrom( 'omas@oras-seed-request-distribution.com', 'LGU Oras' );
        $email->setTo( $emailAcc );
        $email->setSubject( '[OMAS ORAS] Farmer Password Reset Instructions' );

        $message = <<<EOD
            <!DOCTYPE html>
            <html>
            <head>
            <meta charset="UTF-8">
            <title>Password Reset Request</title>
            <style>
                body { font-family: Arial, sans-serif; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
                .bg {
                    width: 100%;
                    height: 100%;
                    background-color: #e9e6e6ff;
                    margin: 0;
                    padding-top: 10px;
                    padding-bottom: 10px;
                }
                .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccc; }
                .header { background-color: #007bff; color: #fff; padding: 10px; text-align: center; font-size: 18px; font-weight: bold; border-radius: 4px 4px 0 0; }
                .content { padding: 20px; font-size: 14px; line-height: 1.5; }
                .btn { 
                    display: inline-block; 
                    padding: 10px 15px; 
                    text-decoration: none; 
                    border-radius: 4px; 
                    background: #007bff !important; 
                    color: #ffffff !important; 
                    font-weight: bold; 
                }
                .footer { font-size: 12px; color: #777; text-align: center; padding-top: 10px; border-top: 1px solid #eee; }
            </style>
            </head>
            <body>
            <div class="bg">
                <div class="container">
                    <div class="header">Password Reset Request</div>
                    <div class="content">
                        <p>Dear {$fullname},</p>
                        <p>We received a request to reset your password for the <b>Seed Request and Distribution System</b>.</p>
                        <p>If you made this request, click the button below to reset your password:</p>

                        <p style="text-align:center; margin: 20px 0;"><a href="{$resetLink}" class="btn">Reset Password</a></p>

                        <p><small>⚠ This link will expire in <b>30 minutes</b> and can only be used once.</small></p>

                        <p>If you did not request a password reset, please ignore this email.</p>

                        <p>Sincerely,<br>OMAS Oras Team</p>
                    </div>
                    <div class="footer">
                        &copy; 2025 Seed Request and Distribution System. All rights reserved.
                    </div>
                </div>
            </div>
            </body>
            </html>
            EOD;



        $email->setMessage( $message );
        $email->setMailType( 'html' );
        $email->send();
        if ( !$email->send() ) {
            // Log debug info if email fails
            log_message( 'error', $email->printDebugger( [ 'headers', 'subject', 'body', 'SMTP' ] ) );
        }

        if ( session()->has( 'pass' ) ) {
            session()->remove( 'pass' );
        }

        return $this->response->setJSON( [
            'success' => true
        ] );
    }

    public function submitResetPassword()
    {
        $email    = $this->request->getPost( 'email' );
        $new_pass = $this->request->getPost( 'new_password' );

        $model = new UsersModel();

        // 🔍 Check if email exists
        $user = $model->where( 'email', $email )
            ->where( 'user_type', 'farmer' )
            ->first();

        if ( !$user ) {
            return $this->response->setJSON( [
                'error' => false
            ] );
        }

        // ✅ Hash the password
        $hashedPassword = password_hash( $new_pass, PASSWORD_DEFAULT );

        // ✅ Update password
        $reset = $model->update( $user[ 'users_tbl_id' ], [ 'password' => $hashedPassword ] );


        if ( $reset ) {

            session()->set( 'pass', 'updated' );

            return $this->response->setJSON( [
                'success' => true
            ] );
        }

    }
}
