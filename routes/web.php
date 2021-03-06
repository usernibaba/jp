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
    Route::get('/register','Index\LoginController@register');//注册 前台
    Route::get('/active','Index\LoginController@active'); //激活用户 前台
    Route::get('/login','Index\LoginController@login');//登录 前台
    Route::get('/create','Index\OrderController@create');//
    Route::get('/git','Index\LoginController@git');//github 登录 前台
    Route::post('/registerdo','Index\LoginController@registerdo');//注册 前台
    Route::post('/logindo','Index\LoginController@logindo');//前台 登录
    Route::get('/quit','Index\LoginController@quit');

});
Route::prefix('index')->group(function (){

    Route::get('/login','Index\IndexController@login');//登录
    Route::get('/add','Index\CartController@add');
    Route::get('/sss','Index\CartController@index');//
    Route::get('/start','Index\StartController@start');//
    Route::get('/prize','Index\StartController@prize');//
    Route::get('/cart','Index\CartController@cart');// 购物车
    Route::get('/create','Index\OrderController@create');
    Route::get('/home','Index\HomeController@home');
    Route::get('/search','Index\IndexController@search');
    Route::get('/tianqi','Index\IndexController@tianqi');  // 天气
    Route::get('/guzzlel','Index\IndexController@guzzlel');
    Route::get('/goods','Index\GoodsController@detail');
    Route::get('/fav','Index\GoodsController@fav');
    Route::get('/contentAdd','Index\GoodsController@contentAdd');
    Route::get('/list','Index\GoodsController@list');
    Route::get('/move','Index\MoveController@list');
});
Route::get('/','Index\IndexController@index');







