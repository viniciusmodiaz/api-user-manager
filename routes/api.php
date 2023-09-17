<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Api\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\Api\LoginController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::post('/', 'create');
});

Route::prefix('auth')->group(function() {
    Route::post('login', 
        [LoginController::class, 'login']);
    Route::post('logout', 
        [LoginController::class, 'logout']);
    Route::post('register', 
        [UserController::class, 'register']);
});

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
 
    return ['token' => $token->plainTextToken];
});

Route::prefix('email')->group(function() {
    Route::get('/confirmation/{token}', [UserController::class, 'confirm'])->name('confirmation');
});

Route::get('/orders', function () {
    // Token has the "check-status" or "place-orders" ability...
})->middleware(['auth:sanctum', 'ability:check-status,place-orders']);

Route::get('/profile', function () {
    // Only verified users may access this route...
})->middleware('verified');