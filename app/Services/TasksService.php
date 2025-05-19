<?php

namespace App\Services;

use App\Models\Tasks;
use App\Http\Resources\TasksCollection;
use App\Http\Resources\TasksResource;

class TasksService {

    public function get(array $params = []){

        $perPage = $params['per_page']??10;
        $tasks = [];

        if( isset($params['id']) ){
            return auth()->user()->tasks()->where('id',$params['id'])->with('lists')->first();
        }

        if( isset($params['term']) ){
            $tasks = auth()->user()->tasks()
                ->where('title','like', "%{$params['term']}%")
                ->where('description','like', "%{$params['term']}%")
                ->with('lists')
                ->orderBy('id','desc')
                ->paginate($perPage);    
        }else{
            $tasks = auth()->user()->tasks()->with('lists')->orderBy('id','desc')->paginate($perPage);
        }

        return new TasksCollection($tasks);
    }

    public function create(Array $dataToCreate){
           
        $tasks = auth()->user()->tasks()->create($dataToCreate);
        return $tasks;

    }

    public function update(Array $dataToUpdate){

        $taskToUpdate = auth()->user()->tasks()->find($dataToUpdate['id']);

        if( !$taskToUpdate ){
            throw new \ErrorException('Failed to update, Task not found');
        }

        $tasks = $taskToUpdate
            ->update([
                'title' => $dataToUpdate['title'],
                'description' => $dataToUpdate['description'],
                'warn_me' => $dataToUpdate['warn_me'],
                'starts_at'  => $dataToUpdate['starts_at']
            ]);

        return $tasks;
    }

    public function delete(int $taskId){
        $taskToDelete = auth()->user()->tasks()->find($taskId);

        if( !$taskToDelete ) {
            throw new \ErrorException('Failed to delete Task, Task not found!');;
        }

        return $taskToDelete->delete();

    }

}