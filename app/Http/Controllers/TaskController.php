<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\ViewModels\GetPublicTasksViewModel;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Public/Index', [
            'model' => new GetPublicTasksViewModel($request)
        ]);
    }

    public function create()
    {

    }

    public function edit(Task $task)
    {

    }

    public function show(Task $task)
    {

    }

    public function store()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
