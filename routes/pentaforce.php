<?php


$domain = env('WEBSITE_HOST');


if (!app()->runningInConsole()) {
    if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
        $domain = 'www.' . env('WEBSITE_HOST');
    }
}

Route::fallback(function () {
    return view('errors.404');
});

/*
|--------------------------------------------------------------------------
| PENTAFORCE API
|--------------------------------------------------------------------------
|
*/
// Dashboard
Route::get('dashboard/{user}', 'Pentaforce\DashboardApiController@getDashboardData');

// php artisan make:controller Pentaforce/DashboardApiController
