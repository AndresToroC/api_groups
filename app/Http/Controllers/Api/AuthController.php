<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        } 

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Las credenciales no son correctas'
            ], 400);
        }

        $user = User::whereEmail($request->email)->first();
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'status' => true,
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6|confirmed',
            'color_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        } 
        
        $request['password'] = Hash::make($request->password);
        
        $user = User::create($request->all());
        $token = $user->createToken('token')->plainTextToken;
        
        return response()->json([
            'status' => true,
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Usuario registrado correctamente'
        ], 200);
    }
}
