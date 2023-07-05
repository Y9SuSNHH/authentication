<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ResponseTrait;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

class AuthSession
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return JsonResponse|RedirectResponse|Response
     */
    public function handle(Request $request, Closure $next)
    {
        $token = request()->bearerToken();
        if (!Redis::exists($token)){
            return $this->errorResponse('Unauthorized', [], 401);
        }
        return $next($request);
    }
}
