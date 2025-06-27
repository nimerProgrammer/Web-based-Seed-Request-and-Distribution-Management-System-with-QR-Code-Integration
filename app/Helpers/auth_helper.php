<?php

if ( !function_exists( 'ifLogin' ) ) {
    function ifLogin()
    {
        if ( !session()->get( "user_id" ) ) {

            return redirect()->to( base_url( '/admin/login' ) );
        }

        return null;
    }
}

