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
Route::post('v1/department/delete', 'v1\DepartmentController@delete');
Route::post('v1/major/create', 'v1\MajorController@create');
Route::post('v1/major/list', 'v1\MajorController@list');
Route::post('v1/major/update', 'v1\MajorController@update');
Route::post('v1/major/delete', 'v1\MajorController@delete');
Route::get('v1/teacher/show/officeHolding', 'v1\TeacherController@showOfficeHolding');
Route::get('v1/teacher/show/educationBackground', 'v1\TeacherController@showEducationBackground');
Route::get('v1/teacher/show/qualification', 'v1\TeacherController@showQualification');
Route::get('v1/teacher/show/source', 'v1\TeacherController@showSource');
Route::get('v1/teacher/show/jobTitle', 'v1\TeacherController@showJobTitle');
Route::get('v1/teacher/show/subject', 'v1\TeacherController@showSubject');
Route::get('v1/teacher/show/politics', 'v1\TeacherController@showPolitics');
Route::get('v1/teacher/show/nationality', 'v1\TeacherController@showNationality');
Route::post('v1/teacher/create', 'v1\TeacherController@create');
Route::post('v1/teacher/list', 'v1\TeacherController@list');
Route::post('v1/teacher/update', 'v1\TeacherController@update');
Route::post('v1/teacher/delete', 'v1\TeacherController@delete');
Route::get('v1/student/show/nation', 'v1\StudentController@showNation');
Route::get('v1/student/show/examArea', 'v1\StudentController@showExamArea');
Route::get('v1/student/show/educationLevel', 'v1\StudentController@showEducationLevel');
Route::get('v1/student/show/lengthOfSchool', 'v1\StudentController@showLengthOfSchool');
Route::get('v1/student/show/degree', 'v1\StudentController@showDegree');
Route::post('v1/student/create', 'v1\StudentController@create');
Route::post('v1/student/list', 'v1\StudentController@list');
Route::post('v1/student/update', 'v1\StudentController@update');
Route::post('v1/student/delete', 'v1\StudentController@delete');
Route::get('v1/department/show/all', 'v1\DepartmentController@showAll');
Route::get('v1/major/show/all', 'v1\MajorController@showAll');
Route::post('v1/probe/create', 'v1\ProbeController@create');
Route::post('v1/probe/list', 'v1\ProbeController@list');
Route::post('v1/probe/update', 'v1\ProbeController@update');
Route::post('v1/probe/delete', 'v1\ProbeController@delete');
Route::post('v1/question/create', 'v1\QuestionController@create');
Route::post('v1/probe/list/question', 'v1\ProbeController@listQuestion');
Route::post('v1/question/update', 'v1\QuestionController@update');
Route::post('v1/probe/delete/question', 'v1\ProbeController@deleteQuestion');
Route::post('v1/probe/answer', 'v1\AnswerController@answer');
Route::post('v1/probe/count', 'v1\ProbeController@count');
