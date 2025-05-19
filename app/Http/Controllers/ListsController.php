<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\ListsService;

use App\Http\Resources\ListsResource;
use App\Http\Requests\ListRequest;
use App\Http\Requests\ListGroupRequest;


class ListsController extends Controller
{
    
    public function __construct(public ListsService $listsService ){}

    public function index(int $taskId, Request $request){

        $params['done'] = $request->get('done') ?? null;

        $lists = $this->listsService->get($taskId, $params['done']);
        
        return response()->json($lists, 200);

    }

    public function storeGroupList(int $taskId, ListGroupRequest $request){

        try{

            $postData = $request->all();

            $resultStore = $this->listsService->create($taskId, $postData);

            return response()->json([
                'data' => $resultStore,
                'message' => 'Group List created with success!'
            ],201);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function store(int $taskId, ListRequest $request){

        try{

            $postData = $request->all();
            $resultStore = $this->listsService->create($taskId, $postData);
          
            if( !$resultStore ){
                throw new \ErrorException('Failed to create List');
            }

            return response()->json([
                'data' => $resultStore,
                'message' => 'List created with success!'
            ],201);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function update(int $taskId, ListRequest $request){
          try{

            $postData = $request->all();

            $resultUpdate = $this->listsService->update($taskId, $postData);

            if( !$resultUpdate ){
                throw new \ErrorException('Failed to update list');
            }

            return response()->json([
                'message' => 'List updated with success!' 
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(int $taskId, int $listId){
        try{

            $listDeleted = $this->listsService->delete($taskId, $listId);

            return response()->json([
                'message' => 'List deleted with success!'
            ],200);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
