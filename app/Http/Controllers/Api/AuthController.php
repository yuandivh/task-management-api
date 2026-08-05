<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $request->validate([
            "name"=>"required",
            "email"=>"required|email|unique:users,email",
            "password"=>"required|confirmed"
        ]);

        $user = User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>Hash::make($request->password)
        ]);

        return response()->json([
            "message"=>"User registered successfully",
            "user"=>$user
        ],201);
    }

    public function login(Request $request){
        $credentials = $request->validate([
            "email"=>"required|email",
            "password"=>"required"
        ]);


        //Create token using Sanctum
        // if(!Auth::attempt($credentials))
        //     {
        //         return response()->json([
        //             "message"=>"Invalid credentials"
        //         ],401);
        //     }

        // $user = Auth::user();
        // $user->tokens()->delete();
        // $token = $user->createToken("api_token")->plainTextToken;

        //Create token using JWT

        if(!$token = auth('api')->attempt($credentials))
        {
            return response()->json([
                "message"=>"Invalid credentials"
            ],401);
        }

        return response()->json([
            "token"=>$token,
            "token_type"=>"bearer"
        ],200);
    }

    public function logout(Request $request)
    {
        // Revoke token using sanctum
        // $request->user()->currentAccessToken()->delete();
        // return response()->json([
        //    "message"=>"Logged out successfully",
        // ],200);

        // Revoke token using JWT
        auth('api')->logout();
        return response()->json([
            "message" => "Logged out successfully"
        ],200);
    }

    public function refresh()
    {
        return response()->json([
            'token' => auth('api')->refresh()
        ]);
    }
}

