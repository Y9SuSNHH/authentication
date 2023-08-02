<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Redis;

class RedisLoginService extends AbstractLoginService
{
    public function login(LoginRequest $loginRequest)
    {
        $this->setUser($loginRequest);

        $token = $this->generateRandomToken();
        $key = "token:$token";
        Redis::set($key, json_encode($this->user->toArray()));
        Redis::expire($key, (int) config('auth.access_token') * 60);

        return [
            'access_token' => $token,
        ];
    }
    public function middleware(): bool
    {
        if (!$this->bearerToken) {
            return false;
        }

        $redisKey = sprintf("token:%s", $this->bearerToken);
        if (!Redis::exists($redisKey)){
            return false;
        }
        $userRedis = json_decode(Redis::get($redisKey));

        $user = User::query()->find($userRedis->id);
        auth()->setUser($user);

        return true;
    }
}
