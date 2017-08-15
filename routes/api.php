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

Route::prefix('pet')->group(function() {
    
    Route::post('', 'PetController@create');
    
    Route::put('', 'PetController@update');
    
    Route::get('findByTags/{tags}', 'PetController@findByTags');
    
    Route::get('{id}', 'PetController@findById');
    
    Route::post('{id}', 'PetController@updateById');
    
    Route::delete('{id}', 'PetController@delete');   
    
});

Route::prefix('store')->group(function() {
    
    Route::post('order', 'OrderController@create');
    
    Route::get('order/{id}', 'OrderController@findById');
    
    Route::delete('order/{id}', 'OrderController@delete');    
    
});
