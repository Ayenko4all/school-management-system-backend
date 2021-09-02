<?php

use App\Http\Controllers\SchoolOwner\PayStackPaymentController;
use App\Http\Controllers\SchoolOwner\SchoolController;
use App\Http\Controllers\SchoolOwner\SchoolOwnerController;
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
Route::prefix('v1')->middleware(['json.response'])->group(function (){

    Route::middleware(['role:school-owner','auth:sanctum'])->group(function (){

        Route::prefix('/schools')->group(function (){
            Route::get('/', [SchoolController::class, 'index'])->name('index.school.api');
            Route::post('/create', [SchoolController::class, 'store'])->name('store.school.api');
            Route::get('/{school}', [SchoolController::class, 'show'])->name('show.school.api');
            Route::patch('/{school}/update', [SchoolController::class, 'update'])->name('update.school.api');
            Route::delete('/{school}/delete', [SchoolController::class, 'destroy'])->name('destroy.school.api');
        });

        Route::prefix('/directors')->group(function (){
            Route::get('/', [SchoolOwnerController::class, 'index'])->name('index.director.api');
            Route::post('/director', [SchoolOwnerController::class, 'store'])->name('store.director.api');
            Route::get('/{director}', [SchoolOwnerController::class, 'show'])->name('show.director.api');
            Route::patch('/{director}/update', [SchoolOwnerController::class, 'update'])->name('update.director.api');
            Route::delete('/{director}/delete', [SchoolOwnerController::class, 'destroy'])->name('destroy.director.api');
        });

        Route::prefix('/paystack')->group(function (){
            Route::post('pay', [PayStackPaymentController::class, 'pay'])->name('paystack.pay.api');
            Route::get('verify', [PayStackPaymentController::class, 'verify'])->name('paystack.verify.api');
        });

    });

    Route::post('/payment/webhook', [PayStackPaymentController::class,'handleGatewayWebHook'])->name('paystack.webhook.api');

});




