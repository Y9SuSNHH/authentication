<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ResponseTrait;
use App\Services\Auth\LoginServiceInterface;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginMiddleware
{
    use ResponseTrait;

    public function __construct(private LoginServiceInterface $loginService) {}

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return JsonResponse|RedirectResponse|Response
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse|RedirectResponse
    {
        if (!$this->loginService->middleware()) {
            return $this->unauthorized();
        }
        return $next($request);
    }
}
