<?php

use Illuminate\Support\Facades\Route;



Route::group([
   /*  'middleware' => [
        'verify.authorization.jwt'
    ] */
], function () {

    // UserController
    Route::get('users', 'UserController@index');
    Route::post('users/send-noti', 'UserController@sendNotificationToUSer');

    Route::post('kids/add', 'KidsController@add');
    Route::get('kids/get', 'KidsController@get');
    Route::get('kids/delete', 'KidsController@delete');
    Route::put('kids/update', 'KidsController@update');
    
    Route::post('classroom/add', 'ClassroomController@add');
    Route::get('classroom/get-kids-teacher', 'ClassroomController@getKidsFromTeacher');
    Route::post('classroom/exit-class', 'ClassroomController@exitFromClass');
    Route::post('classroom/teachers', 'ClassroomController@getTeachers');
    

    Route::post('pase-lista/add', 'PaseListaController@add');
    Route::get('pase-lista/get-user', 'PaseListaController@getUser');

});
