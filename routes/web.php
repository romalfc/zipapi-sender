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

Route::get('/', ['as' => 'send_form', 'uses' => 'SendController@create']);
Route::post('/', ['as' => 'send', 'uses' => 'SendController@store']);
Route::get('/history', ['as' => 'history', 'uses' => 'SendController@history']);

Route::get('{any}', function (){
	return redirect()->route('send_form');
});
