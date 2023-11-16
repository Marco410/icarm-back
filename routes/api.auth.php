<?php

use Illuminate\Support\Facades\Route;

Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');
Route::post('update', 'AuthController@update');
Route::post('find', 'AuthController@find');
Route::post('updateFirebase', 'AuthController@updateFirebase');

Route::post('logout', 'AuthController@logout');
Route::post('delete-account', 'AuthController@deleteAccount');

Route::post('refresh', 'AuthController@refresh');