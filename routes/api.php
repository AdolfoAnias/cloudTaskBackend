<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\Auth\ApiAuthController;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::post('register', [ApiAuthController::class, 'register']);
Route::post('login', [ApiAuthController::class, 'login']);

// Rutas protegidas con el middleware auth:api
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [ApiAuthController::class, 'logout']);
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::get('task', [TaskController::class, 'index'])->name('task.index');
Route::get('task/{id}', [TaskController::class, 'show'])->name('task.show');  
Route::post('task', [TaskController::class, 'store'])->name('task.store');
Route::put('task', [TaskController::class, 'update'])->name('task.update');
Route::delete('task/{id}', [TaskController::class, 'destroy'])->name('task.destroy');          
Route::patch('task/{id}/toggle', [TaskController::class, 'toggle']);

Route::get('keyword', [KeywordController::class, 'index'])->name('keyword.index');
Route::get('keyword/{id}', [KeywordController::class, 'show'])->name('keyword.show');  
Route::post('keyword', [KeywordController::class, 'store'])->name('keyword.store');
Route::put('keyword', [KeywordController::class, 'update'])->name('keyword.update');
Route::delete('keyword/{id}', [KeywordController::class, 'destroy'])->name('keyword.destroy');          

// Ruta para obtener todas las palabras clave de una tarea
Route::get('tasks/{task}/keywords', [TaskController::class, 'keywords']);

// Ruta para asociar/deseleccionar palabras clave de una tarea
Route::post('tasks/{task}/keywords', [TaskController::class, 'attachKeywords']);
Route::delete('tasks/{task}/keywords/{keyword}', [TaskController::class, 'detachKeyword']);