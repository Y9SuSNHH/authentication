<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    use ResponseTrait;

    /**
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        try {
            $user = auth()->user();
            return $this->successResponse($user);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}

