<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSessionController;
use App\Http\Controllers\Admin\AdminSubjectController;
use App\Http\Controllers\Admin\AdminTermController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\PayStackPaymentController;
use App\Http\Controllers\Admin\AdminClassController;
use App\Http\Controllers\Admin\AdminTeacherController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserPermissionController;
use App\Http\Controllers\Admin\UserRoleController;
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

        Route::post('/create-user', RegistrationController::class)->name('create.user');

        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('/classrooms')->name('classrooms.')->group(function (){
            Route::get('/', [AdminClassController::class, 'index'])->name('index');
            Route::post('/', [AdminClassController::class, 'store'])->name('store');
            Route::get('/{classroom}', [AdminClassController::class, 'show'])->name('show');
            Route::patch('/{classroom}', [AdminClassController::class, 'update'])->name('update');
            Route::delete('/{classroom}', [AdminClassController::class, 'destroy'])->name('destroy');
            Route::patch('/{classroom}/restore', [AdminClassController::class, 'restore'])->name('restore');
        });

        Route::prefix('/subjects')->name('subjects.')->group(function (){
            Route::get('/', [AdminSubjectController::class, 'index'])->name('index');
            Route::post('/', [AdminSubjectController::class, 'store'])->name('store');
            Route::get('/{subject}', [AdminSubjectController::class, 'show'])->name('show');
            Route::patch('/{subject}', [AdminSubjectController::class, 'update'])->name('update');
            Route::delete('/{subject}', [AdminSubjectController::class, 'destroy'])->name('destroy');
            Route::patch('/{subject}/restore', [AdminSubjectController::class, 'restore'])->name('restore');
        });

        Route::prefix('/sessions')->name('sessions.')->group(function (){
            Route::get('/', [AdminSessionController::class, 'index'])->name('index');
            Route::post('/', [AdminSessionController::class, 'store'])->name('store');
            Route::get('/{session}', [AdminSessionController::class, 'show'])->name('show');
            Route::patch('/{session}', [AdminSessionController::class, 'update'])->name('update');
            Route::delete('/{session}', [AdminSessionController::class, 'destroy'])->name('destroy');
            Route::patch('/{session}/restore', [AdminSessionController::class, 'restore'])->name('restore');
        });

        Route::prefix('/terms')->name('terms.')->group(function (){
            Route::get('/', [AdminTermController::class, 'index'])->name('index');
            Route::post('/', [AdminTermController::class, 'store'])->name('store');
            Route::get('/{term}', [AdminTermController::class, 'show'])->name('show');
            Route::patch('/{term}', [AdminTermController::class, 'update'])->name('update');
            Route::delete('/{term}', [AdminTermController::class, 'destroy'])->name('destroy');
            Route::patch('/{term}/restore', [AdminTermController::class, 'restore'])->name('restore');
        });

        Route::prefix('/paystack')->group(function (){
            Route::post('pay', [PayStackPaymentController::class, 'pay'])->name('paystack.pay.api');
            Route::get('verify', [PayStackPaymentController::class, 'verify'])->name('paystack.verify.api');
        });

        Route::prefix('/roles')->name('roles.')->group(function (){
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::get('/{role}', [RoleController::class, 'show'])->name('show');
            Route::patch('/{role}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
            Route::patch('/{role}/restore', [RoleController::class, 'restore'])->name('restore');
            Route::patch('/{user}/attach', [UserRoleController::class, 'attach'])->name('attach');
            Route::delete('/{user}/detach', [UserRoleController::class, 'detach'])->name('detach');
        });

        Route::prefix('/permissions')->name('permissions.')->group(function (){
            Route::get('/', [PermissionController::class, 'index'])->name('index');
            Route::post('/', [PermissionController::class, 'store'])->name('store');
            Route::get('/{permission}', [PermissionController::class, 'show'])->name('show');
            Route::get('/{permission}/users', [PermissionController::class, 'users'])->name('users');
            Route::get('/{permission}/roles', [PermissionController::class, 'roles'])->name('roles');
            Route::patch('/{user}/attach', [UserPermissionController::class, 'attach'])->name('attach');
            Route::delete('/{user}/detach', [UserPermissionController::class, 'detach'])->name('detach');
        });

        Route::prefix('/users')->name('users.')->group(function (){
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::get('{user}', [AdminUserController::class, 'show'])->name('show');
        });


        Route::prefix('/options')->name('options.')->group(function (){
            Route::get('classrooms', [OptionController::class, 'classrooms'])->name('classrooms');
            Route::get('roles', [OptionController::class, 'roles'])->name('roles');
            Route::get('permissions', [OptionController::class, 'permissions'])->name('permission');

            Route::get('subject/types', [AdminSubjectController::class, 'types'])->name('types');
        });

    });

    Route::post('/payment/webhook', [PayStackPaymentController::class,'handleGatewayWebHook'])->name('paystack.webhook.api');

});




