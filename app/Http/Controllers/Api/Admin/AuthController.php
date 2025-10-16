<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'between:8,50'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken(rand(1000, 9999))->plainTextToken;

        return response()->json([
            'status' => 1,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'accessToken' => $token,
            ]
        ], Response::HTTP_OK);
    }

    public function logout()
    {
        auth('api')->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 1,
        ], Response::HTTP_OK);
    }
}
