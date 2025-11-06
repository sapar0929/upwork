<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Client\HomeController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)
    ->group(function () {
        Route::get('', 'index')->name('home');
    });

Route::controller(DashboardController::class)
    ->middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('', 'index')->name('dashboard');
    });
