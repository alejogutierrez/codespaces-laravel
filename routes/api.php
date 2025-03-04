<?php

use App\Http\Controllers\Api\AccessLogsController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UsersController::class, 'index']);
Route::get('/users/create', [UsersController::class, 'create']);
Route::get('/users/update', [UsersController::class, 'update']);
Route::get('/users/delete/{id}', [UsersController::class, 'delete']);

Route::get('/logs', [AccessLogsController::class, 'index']);
Route::get('/logs/create/{id}', [AccessLogsController::class, 'create']);
Route::get('/logs/{id}', [AccessLogsController::class, 'find']);
