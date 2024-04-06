<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Pentaforce\DashboardApiController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/*
|--------------------------------------------------------------------------
| PENTAFORCE API
|--------------------------------------------------------------------------
|
*/

// Dashboard
Route::get('dashboard/{user}', [DashboardApiController::class, 'getDashboardData']);


// php artisan make:controller Pentaforce/DashboardApiController
