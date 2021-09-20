<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // User registration
    public function register(Request $request) {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
   
        $data['password'] = bcrypt($data['password']);
        
        $user = User::create($data);

        $response = [
            'message' => 'User registration successful',
            'token' =>  $user->createToken('ImageTaggingSystem')->plainTextToken,
            'data' =>  $user
        ];
   
        return response($response, 201);
    }

    // User login
    public function login(Request $request) {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($data)) {
            $token = Auth::user()->createToken('ImageTaggingSystem')->plainTextToken;

            return response()->json([
                'message' => 'User successfully logged in.',
                'email' => $data['email'],
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'error' => 'Unauthorised'
            ], 401);
        }
    }
}
