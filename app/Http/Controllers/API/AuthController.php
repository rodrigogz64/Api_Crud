<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:sanctum')->only('logout');
    }
    
    //login
    public function login(Request $request){
        $data = $request->validate([
            'email' => 'required|exists:users',
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])->first();
        if(!$user || !Hash::check($data['password'], $user->password)){
            return response()->json([
                'message' => 'Bad credentials'
            ],401);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('PrivateKey')->plainTextToken
        ],201);
    }

    //register user
    public function register(Request $request){
        $data = $request->validate([
            'name' => 'required|string|max: 255',
            'email' => 'required|email|max: 255|unique:users,email',
            'password' => 'required|string',
        ]);

        $user = new User($data);
        $user->password = bcrypt($user->password);
        $user->save();

        return response([
            'user' => $user,
            'token' => $user->createToken('PrivateKey')->plainTextToken
        ], 201); //201 succefuly created
    }

    //close the session and delete the token
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'logout'], 200);
    }
}
