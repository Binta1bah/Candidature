<?php

use App\Http\Controllers\API\UserController;
use GuzzleHttp\Middleware;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['middleware' => ['auth:sanctum']], function () {
//     Route::get('/logout', [UserController::class, 'logout']);
// });

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/info', [UserController::class, 'show']);
    Route::get('/refresh', [UserController::class, 'refresh']);
    Route::put('/update', [UserController::class, 'update']);
});

Route::post('/inscription', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);
