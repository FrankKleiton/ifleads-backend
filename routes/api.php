<?php

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

Route::post('/login', 'Auth\LoginController@login');

Route::group(['as' => 'admin.', 'middleware' => ['auth:api', 'onlyAdmin']], function () {
    Route::post('/register', 'Admin\UserController@create')->name('create');
});

Route::group(['namespace' => 'Api', 'middleware' => ['auth:api']], function () {
    Route::apiResource('/materials', 'MaterialController');

    Route::post('/materials/losts', 'LostMaterialController@store')
        ->name('lost.material');

    Route::apiResource('loans', 'LoanController');
});

Route::get('/unauthorized', 'ErrorController@unauthorized')->name('unauthorized');

Route::get('/forbidden', 'ErrorController@forbidden')->name('forbidden');
