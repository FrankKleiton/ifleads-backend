<?php

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

Route::post('/login', 'Auth\LoginController@login');

Route::namespace('Api')->group(function () {
    Route::apiResource('materials', 'MaterialController');
});

Route::group(['namespace' => 'Api', 'middleware' => 'auth:api'], function () {
    Route::apiResource('loans', 'LoanController');
});

Route::get('/unauthorized', 'ErrorController@unauthorized')->name('unauthorized');

Route::get('/forbidden', 'ErrorController@forbidden')->name('forbidden');