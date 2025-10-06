<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/admin')
    ->group(function () {
        Route::controller(AuthController::class)
            ->middleware('throttle:10,1')
            ->group(function () {
                Route::post('login', 'login');
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
