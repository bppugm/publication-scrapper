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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/document', 'DocumentController@index')->name('document.index');
Route::get('/author', 'AuthorController@index')->name('author.index');
Route::get('/author/search', 'AuthorController@search')->name('author.search');
Route::get('/author/{author}', 'AuthorController@show')->name('author.show');
