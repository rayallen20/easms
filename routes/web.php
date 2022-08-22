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
Route::post('v1/user/update', 'v1\UserController@update');
Route::post('v1/user/updatePassword', 'v1\UserController@updatePassword');
Route::post('v1/user/delete', 'v1\UserController@delete');
Route::post('v1/user/show', 'v1\UserController@show');
Route::post('v1/department/create', 'v1\DepartmentController@create');
Route::post('v1/department/list', 'v1\DepartmentController@list');
Route::post('v1/department/update', 'v1\DepartmentController@update');
