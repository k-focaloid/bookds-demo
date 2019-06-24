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

 Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'UserController@logoutApi');
        Route::get('genre','BookController@listGenre');
        Route::post('book/create','BookController@createBook');
        Route::put('book/update','BookController@updateBook');
        Route::delete('book/delete','BookController@deleteBook');
        Route::get('books','BookController@listBooks');
        Route::get('book/{book_id}','BookController@bookDetails');
    });
Route::post('login','UserController@login');
Route::post('signup','UserController@signup');

