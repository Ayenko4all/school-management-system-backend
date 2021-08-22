<?php

use App\Http\Controllers\SiteOwner\ModuleController;
use App\Http\Controllers\SiteOwner\SchoolTypeController;
use App\Http\Controllers\SiteOwner\StateController;
use App\Http\Controllers\SiteOwner\LgaController;
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
Route::prefix('v1/site-owner')->middleware(['role:site-admin','auth:sanctum','json.response'])->group(function (){

    Route::prefix('/modules')->group(function (){
        Route::get('/', [ModuleController::class, 'index'])->name('index.module.api');
        Route::post('/create', [ModuleController::class, 'store'])->name('store.module.api');
        Route::get('/{module}', [ModuleController::class, 'show'])->name('show.module.api');
        Route::patch('/{module}/update', [ModuleController::class, 'update'])->name('update.module.api');
        Route::delete('/{module}/delete', [ModuleController::class, 'destroy'])->name('destroy.module.api');
    });

    Route::prefix('/school-type')->group(function (){
        Route::get('/', [SchoolTypeController::class, 'index'])->name('index.school.type.api');
        Route::post('/create', [SchoolTypeController::class, 'store'])->name('store.school.type.api');
        Route::get('/{schoolType}', [SchoolTypeController::class, 'show'])->name('show.school.type.api');
        Route::patch('/{schoolType}/update', [SchoolTypeController::class, 'update'])->name('update.school.type.api');
        Route::delete('/{schoolType}/delete', [SchoolTypeController::class, 'destroy'])->name('destroy.school.type.api');
    });

    Route::prefix('/states')->group(function (){
        Route::get('/', [StateController::class, 'index'])->name('index.state.api');
        Route::post('/create', [StateController::class, 'store'])->name('store.state.api');
        Route::get('/{state}', [StateController::class, 'show'])->name('show.state.api');
        Route::patch('/{state}/update', [StateController::class, 'update'])->name('update.state.api');
        Route::delete('/{state}/delete', [StateController::class, 'destroy'])->name('destroy.state.api');
    });

    Route::prefix('/lga-areas')->group(function (){
        Route::get('/', [LgaController::class, 'index'])->name('index.lga.api');
        Route::post('/create', [LgaController::class, 'store'])->name('store.lga.api');
        Route::get('/{lgaArea}', [LgaController::class, 'show'])->name('show.lga.api');
        Route::patch('/{lgaArea}/update', [LgaController::class, 'update'])->name('update.lga.api');
        Route::delete('/{lgaArea}/delete', [LgaController::class, 'destroy'])->name('destroy.lga.api');
    });


});


