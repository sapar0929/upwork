<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthAttempt;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $authAttempts = AuthAttempt::with('ipAddress', 'userAgent')
            ->orderBy('id')
            ->get();

        return view('admin.dashboard.index')
            ->with([
                'authAttempts' => $authAttempts,
            ]);
    }
}
