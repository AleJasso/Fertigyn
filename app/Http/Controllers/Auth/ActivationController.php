<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class ActivationController extends Controller
{
    public function activate($id, $hash)
    {
        $user = User::findOrFail($id);

        $expected = sha1($user->email);

        if (!hash_equals($expected, $hash)) {
            abort(403, 'Enlace de activación inválido.');
        }

        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save();
        }

        return view('auth.activation-success', compact('user'));
    }
}
