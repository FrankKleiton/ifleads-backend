<?php

use Illuminate\Support\Facades\Route;

Route::post('/register', 'Auth\RegisterController@register');