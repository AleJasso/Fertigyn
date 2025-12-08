<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pw = (string)$value;

        if (strlen($pw) < 8) $fail('La contraseña debe tener al menos 8 caracteres.');
        if (!preg_match('/[A-Z]/', $pw)) $fail('Debe incluir al menos una mayúscula.');
        if (!preg_match('/[a-z]/', $pw)) $fail('Debe incluir al menos una minúscula.');
        if (!preg_match('/[^A-Za-z0-9]/', $pw)) $fail('Debe incluir al menos un carácter especial.');

        // No permitir números consecutivos (asc/desc) de 3+
        $digits = preg_replace('/[^0-9]/', '', $pw);
        for ($i=0; $i < strlen($digits)-2; $i++) {
            $a = (int)$digits[$i]; $b = (int)$digits[$i+1]; $c = (int)$digits[$i+2];
            if (($b === $a+1 && $c === $b+1) || ($b === $a-1 && $c === $b-1)) {
                $fail('No se permiten números consecutivos.');
                break;
            }
        }

        // No permitir letras consecutivas (asc/desc) de 3+
        $letters = preg_replace('/[^A-Za-z]/', '', $pw);
        $letters = strtolower($letters);
        for ($i=0; $i < strlen($letters)-2; $i++) {
            $a = ord($letters[$i]); $b = ord($letters[$i+1]); $c = ord($letters[$i+2]);
            if (($b === $a+1 && $c === $b+1) || ($b === $a-1 && $c === $b-1)) {
                $fail('No se permiten letras consecutivas.');
                break;
            }
        }
    }
}
