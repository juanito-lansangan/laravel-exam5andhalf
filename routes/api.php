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

Route::resource('document', 'Document');

Route::post('pet', 'PetController@create');

Route::put('pet/', 'PetController@update');

Route::get('findByTags/{tags}', 'PetController@findByTags');

Route::get('pet/{id}', 'PetController@findById');

Route::post('pet/{id}', 'PetController@updateById');

Route::delete('pet/{id}', 'PetController@delete');