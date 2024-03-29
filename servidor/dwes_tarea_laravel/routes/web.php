<?php

use App\Http\Controllers\AlumnosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|


Route::get('/', function () {
    return view('welcome');
});

Route::resource('/cursos', 'App\Http\Controllers\CursosController');
Route::resource('/categorias', 'CategoriasController');
Route::resource('/alumnos', 'AlumnosController');
*/
Route::get('/alumnos', 'AlumnosController@index');

