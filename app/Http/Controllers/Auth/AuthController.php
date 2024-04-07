<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Notifications\Auth\WelcomeNotification;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\SendPasswordResetLinkRequest;

class AuthController extends Controller {

    public function registration( RegistrationRequest $request ) {

        $user = User::create( $request->only( 'name', 'username', 'email', 'password' ) );

        $accessToken = $user->createToken( 'accessToken' )->accessToken;

        $user->notify( new WelcomeNotification() );

        // Notification::send( $user, new WelcomeNotification() );

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

        $loggedIn = Http::asForm()->post( 'http://127.0.0.1:8001/oauth/token', [
            'grant_type'    => 'password',
            'client_id'     => env( 'CLIENT_ID_PASSPORT' ),
            'client_secret' => env( 'CLIENT_SECRET_PASSPORT' ),
            'username'      => $request->email,
            'password'      => $request->password,
        ] );

        $response = $loggedIn->json();

        return response()->json( [
            'id'           => $response['user_id'],
            'name'         => $response['name'],
            'username'     => $response['username'],
            'email'        => $response['email'],
            'access_token' => $response['access_token'],
            'token_type'   => $response['token_type'],
            'expires_in'   => $response['expires_in'],
        ] );

    }

    public function logout( LogoutRequest $request ) {

        $request->user()->token()->revoke();

        return response()->json( [
            'message' => 'Successfully logged out',
        ] );
    }

    public function changePassword( ChangePasswordRequest $request ) {
        // Write code
    }

    public function sendPasswordResetLink( SendPasswordResetLinkRequest $request ) {

        if ( !User::whereEmail( $request->email )->exists() ) {
            return response()->json( [
                'message' => 'User not found',
            ], 404 );
        }

        Password::sendResetLink( $request->only( 'email' ) );

        return response()->json( [
            'message' => 'We have e-mailed your password reset link!',
        ] );
    }

    public function resetPassword() {

    }

}
