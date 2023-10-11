<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Termwind\Components\Hr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Tidak ada akun dengan email tersebut'],
            ]);
        }
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Password salah'],
            ]);
        }
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'jwt-token' => $token,
            'user' => new UserResource($user),

        ]);
    }

    public function register(Request $request)
    {

        // $request->validate([
        //     'name' => 'required|string',
        //     'email' => 'required|string|email|unique:users,email',
        //     'password' => 'required'
        // ]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'role' => 'user',
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        // $token = $user->createToken('api-token')->plainTextToken;
        // return response()->json([
        //     'jwt-token' => $token,
        //     'user' => new UserResource($user),

        // ]);

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'role' =>  'admin',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'jwt-token' => $token,
            'admin-' => new UserResource($user),

        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    public function updateFcmToken(Request $request)
    {
        // $request->validate([
        //     'fcm_token' => 'required'
        // ]);

        // $request->user()->update([
        //     'fcm_token' => $request->fcm_token
        // ]);

        // return response()->json([
        //     'message' => 'Token berhasil diupdate'
        // ]);

        $request->validate([
            'fcm_token' => 'required'
        ]);

        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'message' => 'Token berhasil diupdate'
        ]);
    }
}
