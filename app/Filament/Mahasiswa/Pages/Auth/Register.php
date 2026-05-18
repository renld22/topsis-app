<?php

namespace App\Filament\Mahasiswa\Pages\Auth;

use Filament\Auth\Http\Responses\Contracts\RegistrationResponse;
use Filament\Auth\Pages\Register as BaseRegister;

class Register extends BaseRegister
{
    public function register(): ?RegistrationResponse
    {
        $response = parent::register();

        auth()->logout();

        $this->redirect('/mahasiswa/login');

        return $response;
    }
}