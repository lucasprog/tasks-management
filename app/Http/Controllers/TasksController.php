<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use App\Services\TasksService;

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

        return $this->tasksService->get($params);

    }

    public function store(){}

    public function update(){}

    public function delete(){}

}
