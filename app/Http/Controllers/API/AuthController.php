<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
   
        $data['password'] = bcrypt($data['password']);
        
        $user = User::create($data);

        $response = [
            'message' => 'User registration successful',
            'token' =>  $user->createToken('MyAuthApp')->plainTextToken,
            'data' =>  $user
        ];
   
        return response($response, 201);
    }
}
