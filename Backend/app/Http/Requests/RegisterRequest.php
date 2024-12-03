<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'confirmed'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The user name is required.',
            'name.string' => 'The user name must be a string.',
            'name.max' => 'User name exceeded the maximum length of 255 characters.',
            'email.required' => 'An email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'The email is already in use.',
            'password.required' => 'The password is required.',
            'password.confirmed' => 'The confirm password does not match.',
            'password.min' => 'The password must be at least 8 characters long.',
        ];
    }
}
