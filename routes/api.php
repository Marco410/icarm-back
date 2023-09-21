<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::get('api-test', [UserController::class,'test']);

Route::get('eventos', 'UserController@test');

Route::get('eventos/lista-eventos', 'EventoController@lista_eventos');
Route::get('eventos/lista-grupos', 'EventoController@lista_grupos');
Route::post('eventos/crear', 'EventoController@create');

Route::post('denuncia/crear', 'DenunciaController@create');
*/
Route::get('test', 'UserController@index');

Route::get('list-event','EventoController@showEvents');


Route::group([
    'middleware' => [
        'verify.authorization.jwt'
        ]
    ], function () {

    Route::post('create-event','EventoController@createEvent');
    Route::post('update-event','EventoController@update');
    /*    
    Route::post('api-test', 'UserController@test');

    Route::get('users', 'UserController@index');

    Route::get('bebes', 'BebeController@index');
    Route::post('bebes/crear', 'BebeController@create');
    Route::get('bebes/lista', 'BebeController@get_bebes');

    Route::post('bebes/registro-toma-leche', 'BebeController@registro_toma_leche');
    Route::post('bebes/registro-panales', 'BebeController@registro_panales');
    Route::post('bebes/registro-crecimiento', 'BebeController@registro_crecimiento');
    Route::post('bebes/get-peso', 'BebeController@get_peso');
    Route::post('bebes/get-talla', 'BebeController@get_talla');
    Route::post('bebes/get-toma-leche', 'BebeController@get_toma_leche');

    Route::post('eventos/registrar-asistencia', 'EventoController@registrar_asistencia');
    */
});