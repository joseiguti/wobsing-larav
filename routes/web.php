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

Route::middleware('origin.session')->group(function () {
    // Aquí van las rutas protegidas que requieren almacenar la cookie de origen de sesión
});

Route::middleware('two.factor')->group(function () {
    // Aquí van las rutas protegidas que requieren autenticación por Two Factor
});

Route::get('/sesion-expirada', function () {
    return view('sesion-expirada');
});
