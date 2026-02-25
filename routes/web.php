<?php

use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\JustOrangeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [JustOrangeController::class, 'index']);
Route::get('/dl-flow/{uniqid}', [JustOrangeController::class, 'dlFlow']);
Route::get('/docs', [DocumentationController::class, 'index']);
Route::get('/docs/{slug}', [DocumentationController::class, 'detail']);
Route::get('/invoice/{invoice}', [JustOrangeController::class, 'invoice']);
Route::get('/plan/invoice/{invoice}', [JustOrangeController::class, 'planInvoice']);
Route::get('/plan/{plan_name}', [JustOrangeController::class, 'plan']);
