<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['api', 'auth:sanctum']], function(){
    Route::post('/services/{service}/send', [App\Http\Controllers\Api\ApiController::class, 'send'])
                    ->name('sendby.service')
                    ->missing(function (Request $request) {
                            abort(404);
    });
/*     Route::get('/notfound', [App\Http\Controllers\Api\ApiController::class, 'notfound'])->name('api.notfound');
    Route::get('/services', [App\Http\Controllers\Api\ServiceController::class, 'index'])->name('service.index');
    Route::get('/services/{service}/show', [App\Http\Controllers\Api\ServiceController::class, 'show'])->name('service.show');
    Route::get('/templates', [App\Http\Controllers\Api\TemplateController::class, 'index'])->name('template.index');
    Route::get('/audit/emails', [App\Http\Controllers\Api\AuditController::class, 'emails'])->name('audit.emails.index'); */
});