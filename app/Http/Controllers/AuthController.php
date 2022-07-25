<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'min:6', 'max:30']
        ]);

        try {
            $response = Http::asForm()->post(config('auth.oauth.base_url') . '/oauth/token', [
                'grant_type' => config('auth.oauth.grant_type'),
                'client_id' => config('auth.oauth.client_id'),
                'client_secret' => config('auth.oauth.client_secret'),
                'username' => $request->get('email'),
                'password' => $request->get('password'),
                'scope' => '*',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

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

        $response = Http::asForm()->post(config('auth.oauth.base_url') . '/oauth/token', [
            'grant_type' => config('auth.oauth.grant_type'),
            'client_id' => config('auth.oauth.client_id'),
            'client_secret' => config('auth.oauth.client_secret'),
            'username' => $request->get('email'),
            'password' => $request->get('password'),
            'scope' => '*',
        ]);

        return $response->json();

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

    // todo write tests for this
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255']
        ]);

        $user = User::where('email', $request->get('email'))->first();

        if ($user) {
            Password::sendResetLink($request->only('email'));
            return response()->json([
                'message' => 'Password reset link has been sent to your email.'
            ]);
        } else {
            return response()->json([
                'message' => 'No user was found with that email.'
            ], 404);
        }
    }

    // todo write tests for this
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $response = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ]);
            $user->save();
        });

        if ($response === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password successfully reset.'
            ]);
        } else {
            return response()->json([
                'message' => 'Password reset failed.'
            ], 500);
        }
    }
}
