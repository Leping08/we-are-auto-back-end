<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'email', 'max:255'],
            'password' => ['required', 'min:6', 'max:30']
        ]);

        $response = Http::asForm()->post(config('auth.oauth.base_url') . '/oauth/token', [
            'grant_type' => config('auth.oauth.grant_type'),
            'client_id' => config('auth.oauth.client_id'),
            'client_secret' => config('auth.oauth.client_secret'),
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'scope' => '*',
        ]);

        return $response->json();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users', 'max:255'],
            'password' => ['required', 'min:6', 'max:30', 'confirmed']
        ]);

        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password'))
        ]);

        //TODO send email verification email
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->tokens()->delete();
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        } else {
            return response()->json([
                'message' => 'No user logged in'
            ]);
        }
    }
}
