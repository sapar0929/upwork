<?php

use App\Http\Controllers\Api\Freelancer\AuthController;
use App\Http\Controllers\Api\Freelancer\DashboardController;
use App\Http\Controllers\Api\Freelancer\VerificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/freelancer')
    ->group(function () {
        Route::controller(VerificationController::class)
            ->middleware('throttle:10,1')
            ->group(function () {
                Route::post('verify', 'verify');
                Route::post('confirm', 'confirm');
            });

        Route::controller(AuthController::class)
            ->middleware('throttle:10,1')
            ->group(function () {
                Route::post('login', 'login');
                Route::post('register', 'register');
                Route::post('recover', 'recover');
                Route::post('logout', 'logout')->middleware('auth:sanctum');
            });

        Route::middleware('auth:sanctum')
            ->prefix('auth')
            ->group(function () {
                Route::controller(DashboardController::class)
                    ->group(function () {
                        Route::get('dashboard', 'index');
                    });
            });
    });
