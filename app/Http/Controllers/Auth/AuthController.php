<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegistrationRequest;

class AuthController extends Controller {

    public function registration( RegistrationRequest $request ) {
        $name     = $request->name;
        $email    = $request->email;
        $username = $request->username;
        $password = $request->password;

        $user = User::create( [
            'name'     => $name,
            'username' => $username,
            'email'    => $email,
            'password' => $password,
        ] );

        $accessToken = $user->createToken( 'accessToken' )->accessToken;

        return response( [
            'id'           => $user->id,
            'name'         => $user->name,
            'username'     => $user->username,
            'email'        => $user->email,
            'access_token' => $accessToken,
        ] );
    }

    public function login( LoginRequest $request ) {

        if ( !User::whereEmail( $request->email )->exists() ) {
            return response()->json( [
                'message' => 'User not found',
            ], 404 );
        }

        $response = Http::asForm()->post( 'http://127.0.0.1:8001/oauth/token', [
            'grant_type'    => 'password',
            'client_id'     => env( 'CLIENT_ID_PASSPORT' ),
            'client_secret' => env( 'CLIENT_SECRET_PASSPORT' ),
            'username'      => $request->email,
            'password'      => $request->password,
        ] );

        return $response->json();
    }

    public function logout( LogoutRequest $request ) {

        $request->user()->token()->revoke();

        return response()->json( [
            'message' => 'Successfully logged out',
        ] );
    }

}
