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

Route::group(['prefix' => 'docs'], function(){
    Route::get('/', 'DocsController@index')->name('api-list');
    Route::get('/{id}', 'DocsController@detail')->name('api-detail');
    
});

Route::get('/storage/{folder_name}/{year}/{month_name}/{filePath}', 'DocsController@filePath')->where(['filePath' => '.*']);