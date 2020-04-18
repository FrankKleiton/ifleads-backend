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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->group(function () {
    Route::apiResource('materials', 'MaterialController');
});

/*
---- ROTA DE EXEMPLO DE GERAÇÃO DO TOKEN ----

Route::post('/session', function (Request $request, JsonWebToken $jwt) {
    $user = User::where([
        'email' => $request->input('email'),
        'password' => $request->input('password')
    ])->first();

    if ($user) {

        $token = $jwt->generateToken([
            'id' => $user->id,
            'email' => $user->email
        ]);

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    return response()->json([
        'error' => 'User not found'
    ], 404);
});
*/
