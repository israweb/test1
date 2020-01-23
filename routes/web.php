<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
// Route::get('/redirect', 'Auth\LoginController@redirectToProvider')
// ->name('login.provider')
// ->where('driver', implode('|', config('auth.socialite.drivers',[])));

// Route::get('/callback/', 'Auth\LoginController@handleProviderCallback')
//     ->name('login.callback')
//     ->where('driver', implode('|', config('auth.socialite.drivers',[])));

// Route::get('/redirect', 'SocialAuthGoogleController@redirect');
// Route::get('/callback', 'SocialAuthGoogleController@callback');

Route::get('/home', 'HomeController@index')->name('home');

//  Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
//  Route::get('/callback/{provider}', 'SocialController@callback');

Route::get('{driver}/callback', 'Auth\LoginController@handleProviderCallback')
    ->name('login.callback')
    ->where('driver', implode('|', config('auth.socialite.drivers',[])));

Route::get('redirect/{driver}', 'Auth\LoginController@redirectToProvider')
    ->name('login.provider')
    ->where('driver', implode('|', config('auth.socialite.drivers',[])));
