<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/', 'IndexController@index');
Route::get('/icarm-app', 'IndexController@index');
Route::get('/radio', 'IndexController@radio');
Route::get('/evento/{slug}', 'IndexController@evento');
Route::get('/eliminar-cuenta', 'IndexController@delete_account');


Route::get('/clear', function () {
    Artisan::call('cache:clear');
   Artisan::call('config:clear');
   Artisan::call('config:cache');
   Artisan::call('view:clear');

   return "Cleared!";
 });