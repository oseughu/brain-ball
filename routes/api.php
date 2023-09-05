<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatsController;
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
    Route::post('/search-players', [StatsController::class, 'searchPlayers'])
        ->name('players.search');

    Route::post('/get-player-stats', [StatsController::class, 'getPlayerStats'])
        ->name('players.stats');

    Route::post('/get-market-value', [StatsController::class, 'getMarketValue'])
        ->name('players.market-value');

    Route::get('/get-competitions', [StatsController::class, 'getCompetitions'])
        ->name('competitions.get');

    Route::post('/get-seasons', [StatsController::class, 'getSeasons'])
        ->name('seasons.get');

    Route::post('/get-teams', [StatsController::class, 'getTeams'])
        ->name('teams.get');

    Route::post('/get-squad', [StatsController::class, 'getSquad'])
        ->name('squad.get');
});

// Route::prefix('admin')
//     ->group(__DIR__ . '/api/admin.php');
// Route::prefix('players')
//     ->group(__DIR__ . '/api/agents.php');
