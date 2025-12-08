<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array {
        return [
            'email' => ['required','email'],
            'password' => ['required','string'],
            'g-recaptcha-response' => ['required','captcha'], // valida con paquete
        ];
    }
    public function messages(): array {
        return [
            'g-recaptcha-response.required' => 'Confirma que no eres un robot.',
            'g-recaptcha-response.captcha' => 'Validación reCAPTCHA fallida.',
        ];
    }
}
