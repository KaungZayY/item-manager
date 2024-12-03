<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * login
     * POST /api/v1/login
     * @param email,password
     */
    public function login(LoginRequest $request)
    {
        try {
            $token = auth()->attempt($request->validated());
            if (!$token) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Invalid credentials!',
                ], 401);
            }
            return $this->responseWithToken($token);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * register
     * POST /api/v1/register
     * @param name,email,password
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create($request->validated());

            if (!$user) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'An excepted error occured!',
                ], 500);
            }

            $token = auth()->login($user);
            return $this->responseWithToken($token, 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * user profile
     * Header: Bearer Token
     * GET /api/v1/profile
     */
    public function profile()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Unauthorized access!',
                ], 401);
            }
            return response()->json([
                'status' => 'success',
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * user logout
     * Header: Bearer Token
     * POST /api/v1/logout
     */
    public function logout()
    {
        try {
            auth()->logout();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * token refresh
     * Header: Bearer Token
     * GET /api/v1/refresh
     */
    public function refresh()
    {
        try {
            return $this->responseWithToken(auth()->refresh(),200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    protected function responseWithToken($token, $status = 200)
    {
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ],$status);
    }
}
