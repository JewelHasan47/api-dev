<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get( '/user', function ( Request $request ) {
    return $request->user();
} )->middleware( 'auth:api' );

Route::post( '/registration', [AuthController::class, 'registration'] );

Route::post( '/login', [AuthController::class, 'login'] );

Route::post( '/logout', [AuthController::class, 'logout'] );
