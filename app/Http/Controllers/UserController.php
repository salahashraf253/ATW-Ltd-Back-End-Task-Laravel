<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone_number' => 'required|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => [
                'name' => $user->name,
                'phone_number' => $user->phone_number,
                'updated_at' => $user->updated_at,
                'created_at' => $user->created_at,
                'id' => $user->id,
            ],
            'access_token' => $token,
        ]);
    }

  
    public function login(Request $request)
        {
            $request->validate([
                'phone_number' => 'required',
                'password' => 'required',
            ]);

            $user = User::where('phone_number', $request->input('phone_number'))->first();

            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                return response()->json(['error' => 'Invalid phone number or password'], 401);
            }

            Auth::login($user);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token,
            ]);
        }
}