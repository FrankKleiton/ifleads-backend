<?php

use Illuminate\Support\Facades\Route;

/**
 * @see \app\Providers\RouteServiceProvider::mapAdminRoutes
 */

Route::group(['as' => 'admin.', 'middleware' => ['auth:api', 'onlyAdmin']], function () {
    Route::post('/register', 'Admin\UserController@create')->name('create');
});
