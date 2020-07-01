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

Route::get('/', function () {
    return view('index');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/chat', 'ChatController@viewChat')->name('chat');
Route::get('/chat/{id}', 'ChatController@viewChat')->name('chat.id');
Route::post('/chat/{id}', 'ChatController@sendMessage')->name('chat.id.message');


Route::get('/create', 'ChatController@create');
Route::post('/create', 'ChatController@createChat')->name('chat.create');
