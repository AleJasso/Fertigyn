<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\StrongPassword;
use Illuminate\Validation\Rule;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && optional(auth()->user()->role)->name === 'ADMIN';
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:100'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required', new StrongPassword],
            'role' => [
                'required',
                Rule::exists('roles', 'name'), 
            ],
        ];
    }
}
