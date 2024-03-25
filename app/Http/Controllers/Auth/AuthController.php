<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegistrationRequest;

class AuthController extends Controller {

    public function registration( RegistrationRequest $request ) {
        $name     = $request->name;
        $email    = $request->email;
        $password = bcrypt( $request->password );

        $user = User::create( [
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make( $password ),
        ] );

        $accessToken = $user->createToken( 'Personal Access Token' )->accessToken;

        return response( [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'access_token' => $accessToken,
        ] );
    }

    public function login( LoginRequest $request ) {
        $http = new Client();

        $user = User::where( 'email', $request->email )->first();

        if ( !$user ) {
            return response()->json( [
                'message' => 'Email or Password incorrect.',
            ], 401 );
        }

        $response = $http->post( route( 'http://127.0.0.1:8000/oauth/token' ), [
            'form_params' => [
                'grant_type'    => 'password',
                'client_id'     => env( 'CLIENT_ID' ),
                'client_secret' => env( 'CLIENT_SECRET' ),
                'username'      => $user->email,
                'password'      => $request->password,
                'scope'         => '',
            ],
        ] );

        return $response->getBody();
    }

    public function logout( LogoutRequest $request ) {
        // Write code
    }

}
