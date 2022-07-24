<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show()
    {
        return Auth::user();
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $this->authorize('update', $user);

        $validated = collect($request->validated());

        // update name if its send in the request
        if ($validated->has('name')) {
            $user->name = $validated->get('name');
            $user->save();
        }

        // update email if its send in the request
        // its already been checked in the form request for unique email
        if ($validated->has('email')) {
            $user->email = $validated->get('email');
            $user->save();
        }

        // update password if its send in the request
        if ($validated->has('password')) {
            $user->password = Hash::make($validated->get('password'));
            $user->save();
        }

        return $user->refresh();
    }
}
