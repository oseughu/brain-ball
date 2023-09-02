<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlayerController;
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

Route::post('/register', [AuthController::class, 'register'])
    ->name('register');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/search-players', [PlayerController::class, 'searchPlayers'])
        ->name('players.search');
});

// Route::prefix('admin')
//     ->group(__DIR__ . '/api/admin.php');
// Route::prefix('players')
//     ->group(__DIR__ . '/api/agents.php');
