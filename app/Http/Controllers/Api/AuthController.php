<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'F_name' => 'required',
            'L_name' => 'required',
            'Education_year' => 'required|integer',
            'faculty' => 'required',
            'profession' => 'required',
            'group' => 'required',
            'job_place' => 'required',
            'contact' => 'required',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $token = $user->createToken('apptoken')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }



    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('apptoken')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
    }


    public function logout(Request $request)
    {
        if ($request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Successfully logged out']);
    }
}
