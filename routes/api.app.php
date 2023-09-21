<?php

use Illuminate\Support\Facades\Route;



Route::group([
   /*  'middleware' => [
        'verify.authorization.jwt'
    ] */
], function () {

    // UserController
    Route::get('users', 'UserController@index');
});
