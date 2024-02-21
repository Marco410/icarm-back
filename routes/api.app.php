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
    Route::get('classroom/teachers', 'ClassroomController@getTeachers');
    

    Route::post('pase-lista/add', 'PaseListaController@add');
    Route::get('pase-lista/get-user', 'PaseListaController@getUser');


    ///ADMIN

    Route::get('iglesias', 'IglesiaController@getAll');

    //Eventos
    Route::get('eventos', 'EventoController@index');
    Route::get('evento', 'EventoController@get');
    Route::post('evento/create', 'EventoController@create');
    Route::post('evento/interested', 'EventoController@create_interested');
    Route::get('evento/get/interested', 'EventoController@getInterested');


    Route::post('encontrado/create', 'EncontradoController@store');
    Route::get('encontrado/invitados', 'EncontradoController@getInvites');
    Route::get('encontrado/invitado', 'EncontradoController@getEncontrado');


    Route::get('storage', function () {
        Artisan::call('storage:link');
       return "Cleared!";
     });

});
