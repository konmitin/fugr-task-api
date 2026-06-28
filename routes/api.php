<?php

use App\Http\Controllers\TaskController;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('tasks', TaskController::class)->missing(function (Request $request) {
    return response([
        'message' => 'Task not found'
    ]);
});
