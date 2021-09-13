<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SendEmailVerificationTokenController;
use App\Http\Controllers\Auth\LogoutController;
Use App\Http\Controllers\Auth\EmailVerificationController;
Use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
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
Route::prefix('v1')->middleware('json.response')->group(function (){
    // public routes
    Route::post('/login', LoginController::class)->name('login.api');
    Route::post('/verify-email-token', EmailVerificationController::class)->name('verifyToken.api');
    Route::post('/request-email-token', SendEmailVerificationTokenController::class)->name('requestEmailToken.api');
    Route::post('/forget-password', ForgotPasswordController::class)->name('requestPasswordToken.api');
    Route::patch('/reset-password', ResetPasswordController::class)->name('resetPassword.api');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/register', RegistrationController::class)->name('register.api');
        Route::delete('/logout', LogoutController::class)->name('logout.api');
    });


});


