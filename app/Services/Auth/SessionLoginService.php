<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class SessionLoginService extends AbstractLoginService
{
    public function login(LoginRequest $loginRequest)
    {
        $this->setUser($loginRequest);
        Auth::login($this->user);
        return [
            'access_token' => session()->getId()
        ];
    }

    public function middleware(): bool
    {
        return Auth::check();
    }
}
