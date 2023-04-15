<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users', [\App\Http\Controllers\UserController::class, 'createUsers']);
Route::put('/users', [\App\Http\Controllers\UserController::class, 'updateUsers']);
Route::get('/users', [\App\Http\Controllers\UserController::class, 'listUsers']);
Route::delete('/users', [\App\Http\Controllers\UserController::class, 'deleteUsers']);

Route::post('/projects', [\App\Http\Controllers\ProjectController::class, 'createProjects']);
Route::put('/projects', [\App\Http\Controllers\ProjectController::class, 'updateProjects']);
Route::put('/link-projects', [\App\Http\Controllers\ProjectController::class, 'linkProjectsToUsers']);
Route::delete('/projects', [\App\Http\Controllers\ProjectController::class, 'deleteProjects']);
Route::get('/projects', [\App\Http\Controllers\ProjectController::class, 'listProjects']);

Route::post('/labels',[\App\Http\Controllers\LabelController::class, 'createLabels']);
Route::put('/link-labels',[\App\Http\Controllers\LabelController::class, 'linkLabelsToProjects']);
Route::delete('/labels',[\App\Http\Controllers\LabelController::class, 'deleteLabels']);
Route::get('/labels', [\App\Http\Controllers\LabelController::class, 'listLabels']);
