<?php

use Illuminate\Support\Facades\Route;

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

Route::get('showTasks', 'TaskController@showTasks');

Route::post('addTask', 'TaskController@addTask');

Route::post('editTask', 'TaskController@editTask');

Route::get('delTask/{Id}', 'TaskController@delTask');