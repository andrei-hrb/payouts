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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/transaction', 'TransactionController@create')->name('transaction')->middleware('auth');
Route::post('/room', 'RoomController@create')->name('create_room')->middleware('auth');
Route::post('/user/quit', 'UserController@quit')->name('quit_room')->middleware('auth');