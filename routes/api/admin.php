<?php

use App\Http\Controllers\Admin\PayStackPaymentController;
use App\Http\Controllers\Admin\AdminClassController;
use App\Http\Controllers\Admin\AdminTeacherController;
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
Route::prefix('v1/admin')->middleware(['json.response'])->group(function (){

    Route::middleware(['role:admin','auth:sanctum'])->group(function (){

        Route::prefix('/class')->group(function (){
            Route::get('/', [AdminClassController::class, 'index'])->name('index.api');
            Route::post('/', [AdminClassController::class, 'store'])->name('store.api');
            Route::get('/{classroom}', [AdminClassController::class, 'show'])->name('show.api');
            Route::patch('/{classroom}', [AdminClassController::class, 'update'])->name('update.api');
            Route::delete('/{classroom}', [AdminClassController::class, 'destroy'])->name('destroy.api');
            Route::patch('/{classroom}/restore', [AdminClassController::class, 'restore'])->name('restore.api');
        });

        Route::prefix('/paystack')->group(function (){
            Route::post('pay', [PayStackPaymentController::class, 'pay'])->name('paystack.pay.api');
            Route::get('verify', [PayStackPaymentController::class, 'verify'])->name('paystack.verify.api');
        });

    });

    Route::post('/payment/webhook', [PayStackPaymentController::class,'handleGatewayWebHook'])->name('paystack.webhook.api');

});




