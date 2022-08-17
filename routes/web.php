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

Route::post('v1/user/login', 'v1\UserController@login');
Route::post('v1/user/logout', 'v1\UserController@logout');
Route::post('v1/user/create', 'v1\UserController@create');
Route::post('v1/user/list', 'v1\UserController@list');
