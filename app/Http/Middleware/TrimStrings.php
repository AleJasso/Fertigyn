<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /** @var array<int, string> */
    protected $except = [
        'password',
        'password_confirmation',
    ];
}
