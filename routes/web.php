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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/signin', 'Web\AuthController@view_login')->name('apps.auth.login');
Route::get('/register', 'Web\AuthController@view_register')->name('apps.auth.register');
Route::get('/home', 'Web\AuthController@view_home');
