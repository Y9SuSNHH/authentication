<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Token;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DatabaseLoginService extends AbstractLoginService
{
    /**
     * @param LoginRequest $loginRequest
     * @return array
     * @throws Exception
     */
    public function login(LoginRequest $loginRequest): array
    {
        $this->setUser($loginRequest);

        $token = $this->createToken();

        return [
            'access_token' => $token->access_token,
            'refresh_token' => $token->refresh_token,
        ];
    }

    public function middleware(): bool
    {
        if (!$this->bearerToken) {
            return false;
        }
        $token = Token::query()->with('user')->where('access_token', $this->bearerToken)->first();

        if (!Carbon::now()->lessThan($token->expires_at) || $token->blacklist){
            return false;
        }
        auth()->setUser($token->user);
        return true;
    }

    /**
     * @return Builder|Model|Token
     * @throws Exception
     */
    public function createToken(): Model|Builder|Token
    {
        $now = Carbon::now();
        return Token::query()->create([
            'user_id' => $this->user->id,
            'access_token' => $this->generateRandomToken(),
            'refresh_token' => $this->generateRandomToken(),
            'expires_at' => $now->addMinutes(config('auth.access_token')),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
