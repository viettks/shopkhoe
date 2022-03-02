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

Route::get('register', function () {
    return view('pages.samples.register');
});

Route::get('register/{skey}', function ($skey) {
    return view('pages.samples.register-step-2')->with('secrect_key', $skey);
});

Route::get('login', function () {
    return view('pages.samples.login');
})->name('login');

Route::group([
    'middleware' => 'auth:api',

], function ($router) {
    Route::get('/main', function ()
    {
        return 'hello world';
    });
});