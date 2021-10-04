<?php

use App\Http\Controllers\Admin\AdminSessionController;
use App\Http\Controllers\Admin\AdminSubjectController;
use App\Http\Controllers\Admin\AdminTermController;
use App\Http\Controllers\Admin\PayStackPaymentController;
use App\Http\Controllers\Admin\AdminClassController;
use App\Http\Controllers\Admin\AdminTeacherController;
use App\Http\Controllers\Auth\RegistrationController;
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

        Route::post('/create-user', RegistrationController::class)->name('create.user.api');

        Route::prefix('/class')->group(function (){
            Route::get('/', [AdminClassController::class, 'index'])->name('index.classroom.api');
            Route::post('/', [AdminClassController::class, 'store'])->name('store.classroom.api');
            Route::get('/{classroom}', [AdminClassController::class, 'show'])->name('show.classroom.api');
            Route::patch('/{classroom}', [AdminClassController::class, 'update'])->name('update.classroom.api');
            Route::delete('/{classroom}', [AdminClassController::class, 'destroy'])->name('destroy.classroom.api');
            Route::patch('/{classroom}/restore', [AdminClassController::class, 'restore'])->name('restore.classroom.api');
        });

        Route::prefix('/subject')->group(function (){
            Route::get('/', [AdminSubjectController::class, 'index'])->name('index.subject.api');
            Route::post('/', [AdminSubjectController::class, 'store'])->name('store.subject.api');
            Route::get('/{subject}', [AdminSubjectController::class, 'show'])->name('show.subject.api');
            Route::patch('/{subject}', [AdminSubjectController::class, 'update'])->name('update.subject.api');
            Route::delete('/{subject}', [AdminSubjectController::class, 'destroy'])->name('destroy.subject.api');
            Route::patch('/{subject}/restore', [AdminSubjectController::class, 'restore'])->name('restore.subject.api');
        });

        Route::prefix('/session')->group(function (){
            Route::get('/', [AdminSessionController::class, 'index'])->name('index.session.api');
            Route::post('/', [AdminSessionController::class, 'store'])->name('store.session.api');
            Route::get('/{session}', [AdminSessionController::class, 'show'])->name('show.session.api');
            Route::patch('/{session}', [AdminSessionController::class, 'update'])->name('update.session.api');
            Route::delete('/{session}', [AdminSessionController::class, 'destroy'])->name('destroy.session.api');
            Route::patch('/{session}/restore', [AdminSessionController::class, 'restore'])->name('restore.session.api');
        });

        Route::prefix('/term')->group(function (){
            Route::get('/', [AdminTermController::class, 'index'])->name('index.term.api');
            Route::post('/', [AdminTermController::class, 'store'])->name('store.term.api');
            Route::get('/{term}', [AdminTermController::class, 'show'])->name('show.term.api');
            Route::patch('/{term}', [AdminTermController::class, 'update'])->name('update.term.api');
            Route::delete('/{term}', [AdminTermController::class, 'destroy'])->name('destroy.term.api');
            Route::patch('/{term}/restore', [AdminTermController::class, 'restore'])->name('restore.term.api');
        });

        Route::prefix('/paystack')->group(function (){
            Route::post('pay', [PayStackPaymentController::class, 'pay'])->name('paystack.pay.api');
            Route::get('verify', [PayStackPaymentController::class, 'verify'])->name('paystack.verify.api');
        });

    });

    Route::post('/payment/webhook', [PayStackPaymentController::class,'handleGatewayWebHook'])->name('paystack.webhook.api');

});




