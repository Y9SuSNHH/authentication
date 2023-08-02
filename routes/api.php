<?php

use App\Http\Controllers;
use App\Http\Middleware\LoginMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login', [Controllers\AuthController::class, 'login']);
Route::post('register', [Controllers\AuthController::class, 'register']);
Route::middleware([LoginMiddleware::class])->group(function () {
    Route::prefix('info')->group(function () {
        Route::get('/', [Controllers\InfoController::class, 'show']);
    });
});
