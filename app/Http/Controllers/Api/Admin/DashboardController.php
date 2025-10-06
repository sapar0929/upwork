<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpAddress;
use App\Models\UserAgent;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 1,
            'data' => [
                'visitors' => Visitor::with('ipAddress', 'userAgent')->get(),
                'userAgents' => UserAgent::withCount('visitors')->get(),
                'ipAddresses' => IpAddress::withCount('visitors')->get(),
            ],
        ], Response::HTTP_OK);
    }
}
