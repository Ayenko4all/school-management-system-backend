<?php

use App\Http\Controllers\SiteOwner\ModuleController;
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
Route::prefix('v1/site/owner')->middleware(['role:site-admin','auth:api','json.response'])->group(function (){

    Route::get('/modules', [ModuleController::class, 'index'])->name('index.module.api');
    Route::post('/create-module', [ModuleController::class, 'store'])->name('store.module.api');
    Route::get('/{module}/module', [ModuleController::class, 'show'])->name('show.module.api');
    Route::patch('/{module}/update', [ModuleController::class, 'update'])->name('update.module.api');

});


