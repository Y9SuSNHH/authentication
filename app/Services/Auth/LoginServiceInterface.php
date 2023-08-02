<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\LoginRequest;

interface LoginServiceInterface
{
    public function login(LoginRequest $loginRequest);

    public function middleware(): bool;
}
