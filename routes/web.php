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
Route::prefix('login')->group(function (){
    Route::get('/register','Index\LoginController@register');
    Route::get('/login','Index\LoginController@login');

});
Route::prefix('index')->group(function (){
    Route::get('/index','Index\IndexController@index');
    Route::get('/login','Index\IndexController@login');

});


