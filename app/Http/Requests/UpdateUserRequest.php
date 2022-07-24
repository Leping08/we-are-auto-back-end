<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['min:2', 'max:255'],
            'email' => ['email', 'unique:users', 'min:4', 'max:255'],
            'old_password' => ['min:6', 'max:30', 'current_password:api'],
            'password' => ['min:6', 'max:30', 'confirmed']
        ];
    }
}
