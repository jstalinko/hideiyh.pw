<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\JustOrangeController;
use App\Http\Controllers\API\BlockerAPIController;
use App\Http\Controllers\PluginValidateController;
use App\Http\Controllers\API\GeolocationAPIController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/releases', [JustOrangeController::class, 'releases']);
Route::post('/validate', PluginValidateController::class)->middleware(['auth.domain']);
Route::post('/order/create',[OrderController::class ,'createTransaction' ]);
Route::group(['middleware' => ['auth.apikey','package','traffic']], function () {
    Route::get('/geolocation/{ip}', GeolocationAPIController::class);
    Route::get('/blocker', BlockerAPIController::class);
});
