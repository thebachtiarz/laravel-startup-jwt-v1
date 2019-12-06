<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', 'APIs\Auth\AuthController@register');
    Route::get('/register/verify', 'APIs\Auth\AuthController@register_verify');
    Route::post('/signin/lost', 'APIs\Auth\AuthController@lost_password');
    Route::get('/signin/lost/verify', 'APIs\Auth\AuthController@lost_password_verify');
    Route::post('/signin/lost/renew-password', 'APIs\Auth\AuthController@password_renew');
});

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('/login', 'APIs\Auth\AuthController@login');
    Route::post('/logout', 'APIs\Auth\AuthController@logout');
    Route::post('/refresh', 'APIs\Auth\AuthController@refresh');
    Route::post('/me', 'APIs\Auth\AuthController@me');
});

Route::group(['middleware' => ['api']], function () {
    Route::get('/allowed-link', 'APIs\AllowedLinkController@getAllowedLink');
});
