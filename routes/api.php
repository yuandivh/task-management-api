<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login'])->middleware('throttle:login');
Route::post('/refresh',[AuthController::class,'refresh']);

Route::middleware(['auth:api','throttle:api'])->group(function (){
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/projects',[ProjectController::class,'index']);
    Route::post('/projects',[ProjectController::class,'store']);
    Route::get('/projects/test_cache',[ProjectController::class,'testCache']);
    Route::get('/projects/{id}',[ProjectController::class,'show']);
    Route::put('/projects/{id}',[ProjectController::class,'update']);
    Route::delete('/projects/{id}',[ProjectController::class,'destroy']);

    Route::get('/projects/{project_id}/tasks',[TaskController::class,'index']);
    Route::get('/tasks/{task_id}',[TaskController::class,'show']);
    Route::post('/projects/{project_id}/tasks',[TaskController::class,'store']);
    Route::put('/tasks/{task_id}',[TaskController::class,'update']);
    Route::delete('/tasks/{task_id}',[TaskController::class,'destroy']);
});
