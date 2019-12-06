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

use App\Mail\LostPasswordMail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/signin', 'Web\AuthController@view_login')->name('apps.auth.login');
Route::get('/register', 'Web\AuthController@view_register')->name('apps.auth.register');
Route::get('/signin/lost', 'Web\AuthController@view_lostpassword')->name('apps.auth.lostpassword');
Route::get('/signin/renew-password', 'Web\AuthController@view_renewpassword');
Route::get('/home', 'Web\AuthController@view_home');
Route::get('/email', function () {
    return new LostPasswordMail();
});
