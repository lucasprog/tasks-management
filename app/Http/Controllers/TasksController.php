<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TasksController extends Controller
{
    
    public function index(){
        return Inertia::render('Tasks/Search');
    }

    public function store(){}

    public function update(){}

    public function delete(){}

}
