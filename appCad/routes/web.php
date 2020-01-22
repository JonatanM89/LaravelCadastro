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

Route::resource('/','CadastroController');
Route::get('/cadastros/getall', 'CadastroController@getAll');
Route::get('/cadastros/{id}', 'CadastroControllerr@get');
Route::post('/cadastros/save', 'CadastroController@save');
Route::delete('/cadastros/delete/{id}', 'CadastroController@delete');
