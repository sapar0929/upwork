<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'integer', 'regex:/^(6[0-5]\d{6}|71\d{6})$/'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $obj = Verification::where('username', $request->username)
            ->whereIn('status', [0, 2]) // Pending, Canceled
            ->where('method', 0) // Phone
            ->where('updated_at', '>', now()->subMinutes(3)) // Last 3 minutes
            ->orderBy('id', 'desc')
            ->first();

        if (!$obj) {
            $obj = Verification::updateOrCreate([
                'username' => $request->username,
                'method' => 0, // Phone
            ], [
                'code' => rand(10000, 99999), // New verification code
                'status' => 0, // Pending
            ]);
        }

        return response()->json([
            'status' => 1,
            'data' => $obj->code,
            'message' => 'Verification code generated successfully.',
        ], Response::HTTP_OK);
    }

    public function confirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'integer', 'regex:/^(6[0-5]\d{6}|71\d{6})$/'],
            'code' => ['required', 'integer', 'between:10000,99999'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $obj = Verification::where('username', $request->username)
            ->where('code', $request->code) // Verification code
            ->whereIn('status', [0, 2]) // Pending, Canceled
            ->where('method', 0) // Phone
            ->where('updated_at', '>', now()->subMinutes(3)) // Last 3 minutes
            ->orderBy('id', 'desc')
            ->first();

        if ($obj) {
            return response()->json([
                'status' => 1,
                'message' => 'Verified successfully.',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid or expired verification code.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
