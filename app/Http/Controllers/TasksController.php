<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Services\TasksService;
use App\Http\Resources\TasksResource;

class TasksController extends Controller
{
    
    public function __construct(public TasksService $tasksService ){}

    public function index(){
        return Inertia::render('Tasks/Search');
    }

    public function get(Request $request){

        $params['id'] = $request->get('id');
        $params['term'] = $request->get('term');
        $params['per_page'] = $request->get('per_page');
        
        $tasks = $this->tasksService->get($params);
        
        return response()->json($tasks, 200);

    }

    public function store(TaskCreateRequest $request){

        try{

            $postData = $request->all();
            $resultStore = $this->tasksService->create($postData);

            if( !$resultStore ){
                throw new \ErrorException('Failed to create Task');
            }

            return response()->json([
                'data' => new TasksResource($resultStore),
                'message' => 'Task created with success!'
            ],201);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function update(TaskUpdateRequest $request){
          try{

            $postData = $request->all();

            $resultUpdate = $this->tasksService->update($postData);

            if( !$resultUpdate ){
                throw new \ErrorException('Failed to update Task');
            }

            return response()->json([
                'message' => 'Task updated with success!' 
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(int $id){
        try{

            $tasksDeleted = $this->tasksService->delete($id);

            return response()->json([
                'message' => 'Task deleted with success!'
            ],200);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
