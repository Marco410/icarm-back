<?php

use Illuminate\Support\Facades\Route;



Route::group([
  /*   'middleware' => [
        'verify.authorization.jwt'
    ]  */
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
    
    Route::post('pago/create', 'PagoController@create');

    //BETELES
    Route::get('betel/get', 'BetelController@getAll');
    Route::post('betel/create', 'BetelController@create');
    Route::post('betel/edit', 'BetelController@update');
    Route::post('betel/delete', 'BetelController@delete');

    ///ADMIN

    Route::get('iglesias', 'IglesiaController@getAll');

    //ADS
    Route::get('ads', 'AdsController@getAll');
    Route::post('ads/create', 'AdsController@create');
    Route::post('ads/delete', 'AdsController@delete');
    
    //Modules
    Route::get('modules', 'ModuleController@getAll');

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

    Route::get('settings/version', 'SettingsController@getAppVersion');

    Route::get('notification/list', 'NotificationsController@list');
    Route::post('notification/seen', 'NotificationsController@seen');
    Route::post('notification/delete', 'NotificationsController@delete');
    Route::post('notification/delete_all', 'NotificationsController@delete_all');
    Route::post('notification/seen_all', 'NotificationsController@seen_all');

    //Servicios
    Route::get('church-services', 'ChurchServiceController@index');
    Route::post('church-services/create', 'ChurchServiceController@create');
    Route::post('church-services/update/{id}', 'ChurchServiceController@update');
    Route::post('church-services/delete/{id}', 'ChurchServiceController@delete');
    
    Route::get('cronjobs/reminderEvent', 'CronjobsController@reminderEvent');

    Route::get('storage', function () {
        Artisan::call('storage:link');
       return "Cleared!";
     });

});
