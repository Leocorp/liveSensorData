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

Route::get('/', array('as' => 'home', 'uses' => 'SensorsController@index'));
Route::name('ldr_log')->get('ldr', [
				'as' => 'ldr_log',
				'uses' => 'SensorsController@ldr',
				]);
Route::post('logData', 'SensorsController@logData');


