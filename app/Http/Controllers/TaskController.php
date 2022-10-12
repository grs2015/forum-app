<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;

use Illuminate\Http\Request;
use App\Actions\DeleteTaskAction;
use App\Actions\UpsertTaskAction;
use App\DataTransferObjects\TaskData;
use App\ViewModels\UpsertTaskViewModel;
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
        return Inertia::render('Public/Form', [
            'model' => new UpsertTaskViewModel()
        ]);
    }

    public function edit(Task $task)
    {
        return Inertia::render('Public/Form', [
            'model' => new UpsertTaskViewModel($task)
        ]);
    }

    public function show(Task $task)
    {
        return Inertia::render('Public/Show', [
            'model' => new UpsertTaskViewModel($task)
        ]);
    }

    public function store(TaskData $data)
    {
        UpsertTaskAction::execute($data);

        return redirect()->action([TaskController::class, 'index']);
    }

    public function update(TaskData $data)
    {
        UpsertTaskAction::execute($data);

        return redirect()->action([TaskController::class, 'edit'], ['task' => $data->slug]);
    }

    public function destroy(Task $task)
    {
        DeleteTaskAction::execute($task);

        return redirect()->action([TaskController::class, 'index']);
    }
}
