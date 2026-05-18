<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as Responsable;

class LoginResponse implements Responsable
{
    public function toResponse($request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect('/admin');
        }

        if ($user->role === 'mahasiswa') {
            return redirect('/mahasiswa');
        }

        return redirect('/');
    }
}