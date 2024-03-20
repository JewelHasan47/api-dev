<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegistrationRequest;

class AuthController extends Controller {

    public function registration( RegistrationRequest $request ) {

    }

    public function login( LoginRequest $request ) {
        $email    = $request->email;
        $password = $request->password;

    }

    public function logout( LogoutRequest $request ) {
        // Write code
    }
}
