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

Route::post('/login', 'Api\Auth\LoginController@login');


Route::group(['namespace' => 'Api', 'middleware' => ['auth:api']], function () {
    Route::post('/admin/register', 'Admin\UserController@create')
        ->middleware(['onlyAdmin'])
        ->name('admin.create');

    Route::apiResource('/materials', 'MaterialController');

    Route::post('/materials/losts', 'LostMaterialController@store')
        ->name('lost.material');

    Route::apiResource('loans', 'LoanController');
});

Route::get('/unauthorized', 'ErrorController@unauthorized')->name('unauthorized');

Route::get('/forbidden', 'ErrorController@forbidden')->name('forbidden');
