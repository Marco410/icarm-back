<?php

use Illuminate\Support\Facades\Route;

Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');
Route::post('update', 'AuthController@update');
Route::post('find', 'AuthController@find');
Route::post('updateFirebase', 'AuthController@updateFirebase');

Route::post('delete', 'AuthController@deleteAccount');

Route::post('logout', 'AuthController@logout');

Route::post('refresh', 'AuthController@refresh');

Route::post('forgot', 'AuthController@forgot');
Route::post('update-password', 'AuthController@updatePassword');