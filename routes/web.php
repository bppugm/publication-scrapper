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
    return redirect()->route('document.index');
});

Auth::routes();

Route::middleware('auth')->prefix('document')->group(function(){
	Route::get('/search', 'DocumentController@search')->name('document.search');
	Route::get('/', 'DocumentController@index')->name('document.index');
});

Route::middleware(['auth'])->prefix('author')->group(function(){
	Route::get('/', 'AuthorController@index')->name('author.index');
	Route::get('/search', 'AuthorController@search')->name('author.search');
	Route::get('/{author}', 'AuthorController@show')->name('author.show');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/crossref', function ()
{
	$view = view('xml-template.crossref')->render();
	return $view;
})->name('crossref.index');
