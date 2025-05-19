<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TasksController;
use App\Http\Controllers\ListsController;


Route::middleware('auth:sanctum')->group(function () {

    Route::get('tasks', [TasksController::class,'get'])->name('tasks.get');
    Route::post('tasks', [TasksController::class,'store'])->name('tasks.store');
    Route::put('tasks', [TasksController::class,'update'])->name('tasks.update');
    Route::delete('tasks/{id}', [TasksController::class,'delete'])->name('tasks.delete');

    Route::middleware('verify_task_owner')->group(function(){
        Route::get('lists/{taskId}', [ListsController::class,'index'])->name('lists.index');
        Route::post('lists/group/{taskId}', [ListsController::class,'storeGroupList'])->name('lists.store-group');
        Route::post('lists/{taskId}', [ListsController::class,'store'])->name('lists.store');
        Route::put('lists/{taskId}', [ListsController::class,'update'])->name('lists.update');
        Route::delete('lists/{taskId}/{listId}', [ListsController::class,'delete'])->name('lists.delete');
    });
});
