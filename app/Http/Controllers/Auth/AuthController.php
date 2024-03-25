<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegistrationRequest;

class AuthController extends Controller {

    public function registration( RegistrationRequest $request ) {
        $name     = $request->name;
        $email    = $request->email;
        $password = $request->password;

        $user = User::create( [
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make( $password ),
        ] );

        $accessToken = $user->createToken( 'accessToken' )->accessToken;

        return response( [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'access_token' => $accessToken,
        ] );
    }

    public function login( LoginRequest $request ) {

        $user = User::where( 'email', $request->email )->first();

        if ( !$user || !Hash::check( $request->password, $user->password ) ) {
            return response()->json( [
                'message' => 'Email or Password incorrect.',
            ], 401 );
        }

        $response = Http::asForm()->post( 'http://127.0.0.1:8000/oauth/token', [
            'grant_type'    => 'password',
            'client_id'     => env( 'CLIENT_ID_PASSPORT' ),
            'client_secret' => env( 'CLIENT_SECRET_PASSPORT' ),
            'username'      => $user->email,
            'password'      => $user->password,
            'scope'         => '*',
        ] );

        return $response->json();
    }

    public function logout( LogoutRequest $request ) {
        // Write code
    }

}
