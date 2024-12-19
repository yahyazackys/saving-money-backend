<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class ApiAuthController extends Controller
{


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|unique:users|email",
            "occupation" => "required",
            "phone_number" => "required|max:13",
            // "phone_prefix" => "required",
            "password" => [
                "required",
                'confirmed',
                'min:5',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Validation',
                'data' => $validator->errors()
            ], 400);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'occupation' => $request->occupation,
                'phone_number' => $request->phone_number,
                // 'phone_prefix' => $request->phone_prefix,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sign Up Successfully!',
                'data' => $user,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Sign Up Failed!',
                'error' => $error->getMessage()
            ], 500);
        }
    }


    public function login(Request $request)
    {
        $data = [
            "email" => $request->email,
            "password" => $request->password,
        ];

        Auth::attempt($data);
        if (Auth::check()) {
            $userId = Auth::user()->id;
            $user = User::where('id', $userId)->first();

            $token = $user->createToken('auth_token')->plainTextToken;
            $cookie = cookie('token', $token, 60 * 1);

            // menggunakan format json
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Login Berhasil',
                    'data' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ],
                200
            )->withCookie($cookie);
        } else {
            $user = null;
            // menggunakan format json
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data Invalid',
                    'data' => null
                ],
                500
            );
        }
    }

    public function logout(Request $request)
    {
        try {
            $removeToken = $request->user()->currentAccessToken()->delete();
            $cookie = cookie()->forget('token');

            if ($removeToken) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Logout Berhasil',
                        'data' => null
                    ],
                    200
                )->withCookie($cookie);
            }
        } catch (Exception $error) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Logout Gagal',
                    'data' => $error->getMessage()
                ],
                500
            );
        }
    }
}
