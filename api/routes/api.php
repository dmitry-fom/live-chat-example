<?php

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('register', 'RegistrationController@register');
    Route::post('verify/{user_id}', 'RegistrationController@verify')
        ->name('email-verify')
        ->middleware('signed');
    
    Route::post('login', 'AuthController@login');
    Route::post('refresh', 'AuthController@refresh');
    
    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::post('logout', 'AuthController@logout');
        Route::get('me', 'AuthController@me');
    });
});

Route::group([
    'prefix' => 'chat',
    'middleware' => 'auth'
], function () {
    Route::post('/send/{user_id}', 'ChatController@send');
    Route::get('/messages/{user_id}', 'ChatController@getChat');
});

Route::group([
    'prefix' => 'user',
    'middleware' => 'auth'
], function () {
    Route::get('/get-users-to-chat', 'UserController@getUsersToChat');
    Route::get('/{id}', 'UserController@getUser');
});


Route::get('test-cookie', function () {
   return response()->json()->withCookie(Cookie::make('test-cookie', 'sdsdsd', domain: 'http://chat.local', httpOnly: false, sameSite: 'none'));
});