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
    Route::get('users/all', 'UserController@getAll');
    Route::get('users/detail', 'UserController@getUser');
    Route::put('users/update', 'UserController@updateUser');
    Route::get('users/roles', 'UserController@getRoles');
    Route::get('users/ministerios', 'UserController@getMinisterios');
    Route::post('users/update-foto-perfil', 'UserController@updateFotoPerfil');
    Route::post('users/delete-foto-perfil', 'UserController@deleteFotoPerfil');
    
    Route::post('kids/add', 'KidsController@add');
    Route::get('kids/get', 'KidsController@get');
    Route::get('kids/get-tutors', 'KidsController@getTutorsByKid');
    Route::get('kids/delete', 'KidsController@delete');
    Route::put('kids/update', 'KidsController@update');
    Route::post('kids/generate-code', 'KidsController@generate_code');
    Route::post('kids/invalidar-code', 'KidsController@invalidar_code');
    Route::post('kids/validar-code', 'KidsController@validar_code');
    
    Route::post('classroom/add', 'ClassroomController@add');
    Route::get('classroom/get-kids-teacher', 'ClassroomController@getKidsFromTeacher');
    Route::post('classroom/exit-class', 'ClassroomController@exitFromClass');
    Route::get('classroom/teachers', 'ClassroomController@getTeachers');
    

    Route::post('pase-lista/add', 'PaseListaController@add');
    Route::get('pase-lista/get-user', 'PaseListaController@getUser');
    Route::put('pase-lista/update-user', 'PaseListaController@updateUser');


    ///ADMIN

    Route::get('iglesias', 'IglesiaController@getAll');

    //Eventos
    Route::get('eventos', 'EventoController@index');
    Route::get('evento', 'EventoController@get');
    Route::post('evento/create', 'EventoController@create');
    Route::post('evento/update', 'EventoController@update');
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
