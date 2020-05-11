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
/**
 * トップページurlパターン.
 * 
 * コントローラ未作成の場合以下のコマンドを入力しコントローラを作成する。
 * $ php artisan make:controller TopController
 */
Route::get('/', 'TopController@index');

/**
 * ガイドページurlパターン.
 * 
 * コントローラ未作成の場合以下のコマンドを入力しコントローラを作成する。
 * $ php artisan make:controller GuideController
 */
Route::get('/s/', 'GuideController@index');

/**
 * 店舗ページurlパターン.
 * 
 * コントローラ未作成の場合以下のコマンドを入力しコントローラを作成する。
 * $ php artisan make:controller roomController
 */
Route::get('/room/{room_id}/', 'RoomController@index');
Route::post('/room/reviewpost/', 'RoomController@reviewPost');
Route::post('/room/reservconfirm/', 'RoomController@reservConfirm');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
