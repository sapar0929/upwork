<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Freelancer;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'integer', 'regex:/^(6[0-5]\d{6}|71\d{6})$/', 'exists:freelancers,username'],
            'password' => ['required', 'string', 'between:8,50'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $freelancer = Freelancer::where('username', $request->username)->first();

        if (!$freelancer || !Hash::check($request->password, $freelancer->password)) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $freelancer->createToken('iPhone 15')->plainTextToken;

        return response()->json([
            'status' => 1,
            'data' => [
                'id' => $freelancer->id,
                'first_name' => $freelancer->first_name,
                'last_name' => $freelancer->last_name,
                'username' => $freelancer->username,
                'accessToken' => $token,
            ],
            'message' => 'Logged in successfully.',
        ], Response::HTTP_OK);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'integer', 'regex:/^(6[0-5]\d{6}|71\d{6})$/', 'unique:freelancers,username'],
            'code' => ['required', 'integer', 'between:10000,99999'],
            'new_password' => ['required', 'string', 'between:8,50'],
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
            ->where('updated_at', '>', now()->subMinutes(5)) // Last 5 minutes
            ->orderBy('id', 'desc')
            ->first();

        if ($obj) {
            $obj->status = 1; // Completed
            $obj->update();

            $freelancer = Freelancer::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'password' => bcrypt($request->new_password),
            ]);

            $token = $freelancer->createToken('iPhone 15')->plainTextToken;

            return response()->json([
                'status' => 1,
                'data' => [
                    'id' => $freelancer->id,
                    'first_name' => $freelancer->first_name,
                    'last_name' => $freelancer->last_name,
                    'username' => $freelancer->username,
                    'accessToken' => $token,
                ],
                'message' => 'Registered successfully.',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid or expired verification code.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function recover(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'integer', 'regex:/^(6[0-5]\d{6}|71\d{6})$/', 'exists:freelancers,username'],
            'code' => ['required', 'integer', 'between:10000,99999'],
            'new_password' => ['required', 'string', 'between:8,50'],
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
            ->where('updated_at', '>', now()->subMinutes(5)) // Last 5 minutes
            ->orderBy('id', 'desc')
            ->first();

        if ($obj) {
            $obj->status = 1; // Completed
            $obj->update();

            $freelancer = Freelancer::updateOrCreate([
                'username' => $request->username,
            ], [
                'password' => bcrypt($request->new_password),
            ]);

            $token = $freelancer->createToken('iPhone 15')->plainTextToken;

            return response()->json([
                'status' => 1,
                'data' => [
                    'id' => $freelancer->id,
                    'first_name' => $freelancer->first_name,
                    'last_name' => $freelancer->last_name,
                    'username' => $freelancer->username,
                    'accessToken' => $token,
                ],
                'message' => 'Recovered successfully.',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid or expired verification code.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function logout()
    {
        auth('freelancer_api')->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 1,
        ], Response::HTTP_OK);
    }
}
