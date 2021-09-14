<?php

use App\Http\Controllers\SiteOwner\ModuleController;
use App\Http\Controllers\SiteOwner\SchoolTypeController;
use App\Http\Controllers\SiteOwner\StateController;
use App\Http\Controllers\SiteOwner\LgaController;
use App\Http\Controllers\Teacher\TeacherController;
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
Route::prefix('v1')->middleware(['auth:sanctum','json.response'])->group(function (){

    Route::prefix('/teacher')->middleware(['role:teacher'])->group(function (){
        Route::get('/', [TeacherController::class, 'index'])->name('index.teacher.api');
        Route::post('/', [TeacherController::class, 'store'])->name('store.teacher.api');
        Route::get('/{teacher}', [TeacherController::class, 'show'])->name('show.teacher.api');
        Route::patch('/{teacher}', [TeacherController::class, 'update'])->name('update.teacher.api');
        Route::delete('/{teacher}/delete', [TeacherController::class, 'destroy'])->name('destroy.teacher.api');
    });

});


