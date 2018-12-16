<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| +
*--+
*/

Route::group(['prefix' => '/', 'middleware' => ['web', 'auth']], function(){
	Route::get('/', 'FrontController@index')->name('front.index');
});

Auth::routes();

//load default route for vue-router
Route::get('/{path}', 'Dashboard\HomeController@index')->where( 'path', '([A-z\d-\/_.]+)?' );