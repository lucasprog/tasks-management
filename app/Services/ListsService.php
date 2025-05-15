<?php

namespace App\Services;

use App\Models\Lists;

use App\Http\Resources\ListsResource;


class ListsService {

    public function get(int $taskId, bool $done = null){

        $lists = Lists::where('tasks_id',$taskId);

        if( !is_null($done) ){
            $lists = $lists->where('done',$done);
        }

        return ListsResource::collection($lists->get());
    }

    public function create(int $taskId, Array $dataToCreate){
           
        $dataToCreate['tasks_id'] = $taskId;

        $listCreated = Lists::create($dataToCreate);

        return new ListsResource($listCreated);

    }

    public function update(int $taskId, Array $dataToUpdate){

        $listToUpdate = Lists::where('tasks_id',$taskId)
            ->where('id', $dataToUpdate['id'])->first();            

        if( !$listToUpdate ){
            throw new \ErrorException('Failed to update, List not found');
        }

        $listUpdated = $listToUpdate
            ->update([
                'item' => $dataToUpdate['item'],
                'done' => $dataToUpdate['done']
            ]);

        return $listUpdated;
    }

    public function delete(int $taskId, int $listId){
        $listToDelete = Lists::where('tasks_id',$taskId)
            ->where('id', $listId)->first();            

        if( !$listToDelete ) {
            throw new \ErrorException('Failed to delete List, List not found!');;
        }

        return $listToDelete->delete();

    }

}   