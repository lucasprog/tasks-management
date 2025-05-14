<?php

namespace App\Services;

use App\Models\Tasks;

class TasksService {

    public function get(array $params = []){

        $perPage = $params['per_page']??10;

        if( isset($params['id']) ){
            return auth()->user()->tasks()->where('id',$params['id'])->with('lists:id,item,done')->first();
        }

        if( isset($params['term']) ){
            return auth()->user()->tasks()
                ->where('title','like', "%{$params['term']}%")
                ->orWhere('description','like', "%{$params['term']}%")
                ->with('lists:id,item,done')
                ->paginate($perPage);    
        }

        return auth()->user()->tasks()->with('lists:id,item,done')->paginate($perPage);
    }

    public function create(){}

    public function update(){}

    public function delete(){}

}