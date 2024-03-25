<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array {
        return [
            'name.required'      => 'Please enter your name.',
            'name.string'        => 'Please enter a valid name.',
            'name.max'           => 'Your name is too long.',
            'username.required'  => 'Please enter your username.',
            'username.string'    => 'Please enter a valid username.',
            'username.max'       => 'Your username is too long.',
            'email.required'     => 'Please enter your email address.',
            'email.string'       => 'Please enter a valid email address.',
            'email.unique'       => 'This email address is already registered.',
            'password.required'  => 'Please enter your password.',
            'password.string'    => 'Please enter a valid password.',
            'password.min'       => 'Your password must be at least 8 characters.',
            'password.confirmed' => 'Your passwords do not match.',
        ];
    }
}
