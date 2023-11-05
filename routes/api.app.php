<?php

use Illuminate\Support\Facades\Route;



Route::group([
   /*  'middleware' => [
        'verify.authorization.jwt'
    ] */
], function () {

    // UserController
    Route::get('users', 'UserController@index');

    Route::post('kids/add', 'KidsController@add');
    Route::get('kids/get', 'KidsController@get');
    Route::get('kids/delete', 'KidsController@delete');
    Route::put('kids/update', 'KidsController@update');

});
