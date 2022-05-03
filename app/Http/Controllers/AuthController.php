<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('SOME_HASH')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(is_null($user)) {
            $response = [
                'msg' => 'User not found !'
            ];
            return response($response, 400);
        }

        if(!Hash::check($fields['password'], $user->password)) {
            $response = [
                'msg' => 'Wrong Password'
            ];
            return response($response, 400);
        }

        $token = $user->createToken('SOME_HASH')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);

    }

    public function profile(Request $request)
    {
        return $request->user();
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        $response = [
            'msg' => "Logged Out"
        ];

        return response($response, 200);
    }
}
