<?php

namespace App\Services\Auth;

use App\Http\Controllers\ResponseTrait;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

abstract class AbstractLoginService implements LoginServiceInterface
{
    use ResponseTrait;

    private Builder|Model $query;
    protected User $user;
    protected ?string $bearerToken;

    public function __construct()
    {
        $this->query = User::query()->getModel();
        $this->bearerToken = request()->bearerToken();
    }

    /**
     * @param LoginRequest $loginRequest
     * @return User|Builder|Model|JsonResponse|bool
     */
    public function setUser(LoginRequest $loginRequest): User|Builder|Model|JsonResponse|bool
    {
        $this->user = $user = $this->query->where('email', $loginRequest->get('email'))->first();
        if (!$user || !Hash::check($loginRequest->get('password'), $this->user->password)) {
            return $this->unauthorized();
        }

        auth()->setUser($user);
        return true;
    }

    /**
     * @param int $length
     * @return string
     * @throws Exception
     */
    protected function generateRandomToken(int $length = 32): string
    {
        // Generate random bytes
        $randomBytes = random_bytes($length);

        // Convert to hexadecimal string
        return bin2hex($randomBytes);
    }

    abstract function login(LoginRequest $loginRequest);

    abstract function middleware(): bool;
}
