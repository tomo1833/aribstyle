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
 * $ php artisan make:controller ShopController
 */
Route::get('/shop/{shop_id}/', 'ShopController@index');
Route::post('/shop/reviewpost/', 'ShopController@reviewPost');
Route::post('/shop/reservconfirm/', 'ShopController@reservConfirm');



/**
Route::get('/', function () {

// TODO あとでみなおす	
    // return view('top');
    return view('welcome');
});
*/
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
