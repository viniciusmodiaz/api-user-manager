<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\Api\LoginController;
use App\Http\Controllers\Auth\Api\RegisterController;

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
        [RegisterController::class, 'register']);
});

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
 
    return ['token' => $token->plainTextToken];
});

Route::prefix('email')->group(function() {
    // Route::get('/verify', function () {
    //     return view('auth.verify-email');
    // })->middleware('auth')->name('verification.notice');
    
    Route::get('/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
     
        return redirect('/home');
    })->middleware(['auth', 'signed'])->name('verification.verify');
    
    Route::post('/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
     
        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
});

Route::get('/orders', function () {
    // Token has the "check-status" or "place-orders" ability...
})->middleware(['auth:sanctum', 'ability:check-status,place-orders']);

Route::get('/profile', function () {
    // Only verified users may access this route...
})->middleware('verified');