<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Auth\AbstractLoginService;
use App\Services\Auth\LoginServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

final class AuthController extends Controller
{
    use ResponseTrait;

    /**
     * @param LoginRequest $loginRequest
     * @param LoginServiceInterface $loginService
     * @return JsonResponse
     */
    public function login(LoginRequest $loginRequest, LoginServiceInterface $loginService): JsonResponse
    {
        try {
            $data = $loginService->login($loginRequest);

            return $this->successResponse($data);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param RegisterRequest $registerRequest
     * @return JsonResponse
     */
    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        try {
            $password = Hash::make($registerRequest->password);
            $user = User::query()->create([
                'name' => $registerRequest->name,
                'email' => $registerRequest->email,
                'password' => $password,
            ]);
            Auth::login($user);
            return $this->successResponse($user);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
