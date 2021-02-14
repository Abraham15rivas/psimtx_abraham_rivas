<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Grupo de rutas de los usuarios autenticados
Route::group(['middleware' => 'auth'], function() {
    // Obtener usuario logeado
    Route::get('/user', function (Request $request) {
        return $request->user()->load('role', 'interests');
    });

    // Home
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/interests', [App\Http\Controllers\HomeController::class, 'interests']);

    // Grupo de rutas de los usuarios comunes
    Route::group([
        'middleware' => 'common'
    ], function() {
        Route::post('/interest/store', [App\Http\Controllers\CommonController::class, 'store']);
        Route::get('/related/users', [App\Http\Controllers\CommonController::class, 'showRelatedUsers']);
    });

    // Grupo de rutas del administrador
    Route::group([
        'middleware' => 'admin',
        'prefix' => 'admin'
    ], function() {
        Route::get('/users/all', [App\Http\Controllers\HomeController::class, 'getAllUsers']);
        Route::get('/interests', [App\Http\Controllers\InterestController::class, 'index']);
    });
});
