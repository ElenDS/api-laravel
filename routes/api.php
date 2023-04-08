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
Route::put('/users', [\App\Http\Controllers\UserController::class, 'updateUser']);
Route::get('/users', [\App\Http\Controllers\UserController::class, 'listUsers']);

Route::post('/project', [\App\Http\Controllers\ProjectController::class, 'createProject']);
Route::put('/project/{project}', [\App\Http\Controllers\ProjectController::class, 'updateProject']);
Route::delete('/project/{project}', [\App\Http\Controllers\ProjectController::class, 'deleteProject']);
Route::get('/projects', [\App\Http\Controllers\ProjectController::class, 'listProjects']);

Route::post('/label',[\App\Http\Controllers\LabelController::class, 'createLabel']);
Route::put('/label/{label}',[\App\Http\Controllers\LabelController::class, 'updateLabel']);
Route::delete('/label/{label}',[\App\Http\Controllers\LabelController::class, 'deleteLabel']);
Route::get('/labels', [\App\Http\Controllers\LabelController::class, 'listLabels']);
