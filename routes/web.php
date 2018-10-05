<?php



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

Route::get('/', 'PlaceToPayController@welcome')->name('welcome');
Route::get('/listar', 'PlaceToPayController@listar')->name('listar');
Route::get('/return', 'PlaceToPayController@redirect')->name('redirect');
Route::post('/store', 'PlaceToPayController@store')->name('store');

