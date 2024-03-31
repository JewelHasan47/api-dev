<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get( '/user', function ( Request $request ) {
    return $request->user();
} )->middleware( 'auth:api' );

Route::post( '/registration', [AuthController::class, 'registration'] );

Route::post( '/login', [AuthController::class, 'login'] );

Route::post( '/logout', [AuthController::class, 'logout'] )->middleware( 'auth:api' );

Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth:api');

Route::post( '/forgot-password', [AuthController::class, 'sendPasswordResetLink'] );

Route::post( '/reset-password/{token}', [AuthController::class, 'resetPassword'] )->name('password.reset');
