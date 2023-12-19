<?php

use App\Http\Controllers\API\CandidatureController;
use App\Http\Controllers\API\FormationController;
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

Route::group(['middleware' => 'admin'], function () {
    Route::put('/cloturer/{formation}', [FormationController::class, 'edit']);
    Route::get('/formationsCloturees', [FormationController::class, 'formationCloturee']);
    Route::get('/formationsNonCloturees', [FormationController::class, 'formationNonCloturee']);
    Route::put('/accepter/{candidature}', [CandidatureController::class, 'accepter']);
    Route::put('/refuser/{candidature}', [CandidatureController::class, 'refuser']);
    Route::get('/candidaturesFormation/{formation}', [CandidatureController::class, 'CandidaturesFormation']);
    Route::get('/candidats', [UserController::class, 'index']);
});

Route::apiResource('formations', FormationController::class);
Route::apiResource('candidatures', CandidatureController::class);

Route::post('/candidater/{formation}', [CandidatureController::class, 'store']);
Route::get('/mesCandidatures', [CandidatureController::class, 'mesCandidatures']);

Route::post('/inscription', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);
