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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/verificacion', function () {
    return view('verificacion');
});

Route::get('/sesiones', function () {
    return view('sesiones');
});

Route::middleware('last.session')->group(function () {
    // Aquí van las rutas protegidas que requieren verificación de última sesión
});
