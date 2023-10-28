<?php

use Illuminate\Support\Facades\Route;

Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');
Route::post('findUserRegister', 'AuthController@findUserRegister');

Route::post('logout', 'AuthController@logout');

Route::post('refresh', 'AuthController@refresh');