<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('articles', 'ArticleController@index');
Route::get('articles/{id}', 'ArticleController@show');
Route::post('articles', 'ArticleController@store');
Route::put('articles/{id}', 'ArticleController@update');
Route::delete('articles/{id}', 'ArticleController@delete');


/* ---------------------------user management -------------------------*/

Route::post('register', 'UserManagement@register');
Route::post('login', 'UserManagement@login');
Route::post('logout', 'UserManagement@logout');

Route::group(['middleware'=>'TokenApi'], function () {
	Route::post('articles_security_check', 'ArticleController@index');
});