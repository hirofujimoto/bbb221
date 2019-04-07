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

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Auth::routes(['verify' => true]);

Route::get('/', 'ArticleController@index');
Route::get('/home','ArticleController@index');
Route::get('/article/index','ArticleController@index')->name('article.index');
Route::get('/article/create', 'ArticleController@create')->name('article.create');
Route::post('/article/store', 'ArticleController@store')->name('article.store');
Route::get('/article/show/{id}', 'ArticleController@show')->name('article.show');
Route::get('/article/unread/{id}', 'ArticleController@unread')->name('article.unread');
Route::get('/article/lastread/{id}','ArticleController@lastread')->name('article.lastread');
Route::get('/article/edit/{id}','ArticleController@edit')->name('article.edit');
Route::post('/article/update','ArticleController@update')->name('article.update');

Route::get('/comment/create/{article_id}', 'CommentController@create')->name('comment.create');
Route::post('/comment/store', 'CommentController@store')->name('comment.store');
Route::get('/comment/show/{id}', 'CommentController@show')->name('comment.show');
Route::get('/comment/edit/{id}', 'CommentController@edit')->name('comment.edit');
Route::post('/comment/update','CommentController@update')->name('comment.update');

