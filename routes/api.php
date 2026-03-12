<?php

use App\Http\Controllers\ScheduleSession\SessionController;
use App\Http\Controllers\SessionOrderController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('user-list', [UsersController::class, 'indexApi'])->middleware('auth:sanctum');

Route::get('grade-session', [SessionController::class, 'listForfront'])->middleware('auth:sanctum');

Route::post('session-order', [SessionOrderController::class, 'store'])->middleware('auth:sanctum');

