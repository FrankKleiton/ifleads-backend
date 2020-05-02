<?php

use Illuminate\Support\Facades\Route;

/**
 * @see \app\Providers\RouteServiceProvider::mapAdminRoutes
 */

Route::group(['as' => 'admin.', 'middleware' => 'auth:api'], function () {
    Route::post('/register', 'Auth\RegisterController@register');
});
