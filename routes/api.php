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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/authors', 'AuthorController@index');
Route::get('/authors/{author}', 'AuthorController@show');

Route::get('/documents', 'DocumentController@index');
Route::get('/documents/show', 'DocumentController@show');
Route::get('/documents/metrics', 'DocumentMetricController@index');

Route::get('/scimago', 'ScimagoController@index');
Route::get('/scimago/{id}', 'ScimagoController@show');
