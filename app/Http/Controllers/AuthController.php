<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

final class AuthController extends Controller
{
    use ResponseTrait;

    public function login(LoginRequest $loginRequest)
    {
        try {
            $user = User::query()->where('email', $loginRequest->get('email'))->firstOrFail();
            if (!Hash::check($loginRequest->get('password'), $user->password)) {
                return redirect()->route('signin')->with('failed', 'Sai mật khẩu');
            }

            Auth::login($user);
            $token = hash('sha256', session()->getId());
            $user->remember_token = $token;
            $user->save();
            $key = "token:$token";
            Redis::set($key, json_encode($user->toArray()));
            Redis::expire($key, getenv('SESSION_LIFETIME') * 60);

            return $this->successResponse([
                'token' => $token,
            ]);
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
