<?php

use App\Models\Task;
use App\Http\Controllers\TaskController;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Inertia\Testing\AssertableInertia as Assert;

/* ------------------------------ @index method ----------------------------- */
it('renders task list page with Inertia', function() {
    $this->withoutExceptionHandling();

    $tasks = Task::factory()->count(2)->state(new Sequence(['title' => 'First task'], ['title' => 'Second task']))->create();
    $titleFirstTask = $tasks[0]->title;
    $titleSecondTask = $tasks[1]->title;

    $response = $this->get(action([TaskController::class, 'index']));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Public/Index')
        ->has('model', fn (Assert $page) => $page
            ->has('tasks', fn (Assert $page) => $page
                ->has('data', 2)
                ->has('data.0', fn (Assert $page) => $page
                    ->where('title', $titleFirstTask)
                    ->etc())
                ->has('data.1', fn (Assert $page) => $page
                    ->where('title', $titleSecondTask)
                    ->etc())
                ->etc())
            ->etc()) );
});

/* ----------------------------- @create method ----------------------------- */
it('renders create task form with Inertia', function() {
    $response = $this->get(action([TaskController::class, 'create']));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Public/Form')
        ->has('model', fn (Assert $page) => $page
            ->has('task')
            ->missing('task.title')
            ->etc())
        );
});

/* ------------------------------ @edit method ------------------------------ */
it('renders edit task form with Inertia', function() {
    $task = Task::factory()->create();

    $title = $task->title;

    $response = $this->get(action([TaskController::class, 'edit'], ['task' => $task->slug]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Public/Form')
        ->has('model', fn (Assert $page) => $page
            ->has('task', fn (Assert $page) => $page
                ->where('title', $title)
                ->etc()
                )
            )
        );
});

/* ------------------------------ @show method ------------------------------ */
it('renders single task entry with Inertia', function() {
    $task = Task::factory()->create();

    $title = $task->title;

    $response = $this->get(action([TaskController::class, 'show'], ['task' => $task->slug]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Public/Show')
        ->has('model', fn (Assert $page) => $page
            ->has('task', fn (Assert $page) => $page
                ->where('title', $title)
                ->etc()
                )
            )
        );
});

/* ------------------------------ @store method ----------------------------- */
it('checks the task data validation and redirect', function() {
    $this->withoutExceptionHandling();

    $taskData = [
        'title' => 'Task Title',
        'description' => 'Task Description',
    ];

    $response = $this->post(action([TaskController::class, 'store']), $taskData);

    $response->assertStatus(302);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('public.index'));
});

it('checks the session error when validation fails', function() {
    $taskData = [
        'description' => 'Only description, without title'
    ];

    $response = $this->post(action([TaskController::class, 'store']), $taskData);

    $response->assertSessionHasErrors();
});

it('checks the stored task is in database', function() {
    $taskData = [
        'title' => 'Task Title',
        'description' => 'Task Description',
    ];

    $response = $this->post(action([TaskController::class, 'store']), $taskData);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('tasks', ['title' => 'Task Title']);
});

/* ----------------------------- @update method ----------------------------- */
it('checks the validation and redirect at update', function() {
    $task = Task::factory()->create();

    $taskData = [
        'title' => 'Task Title',
        'description' => 'Task Description',
        'id' => $task->id
    ];

    $response = $this->put(action([TaskController::class, 'update'], ['task' => $task->slug]), $taskData);
    $task->refresh();

    $response->assertSessionHasNoErrors();
    $response->assertStatus(302);
    $response->assertRedirect(action([TaskController::class, 'edit'], ['task' => $task->slug]));
});

it('checks the updated task is in database', function() {
    $task = Task::factory()->create();

    $taskData = [
        'title' => 'Updated Task Title',
        'description' => 'Task Description',
        'id' => $task->id
    ];

    $response = $this->put(action([TaskController::class, 'update'], ['task' => $task->slug]), $taskData);
    $task->refresh();

    $this->assertDatabaseHas('tasks', [
        'title' => 'Updated Task Title',
    ]);
    $response->assertStatus(302);
    $response->assertRedirect(action([TaskController::class, 'edit'], ['task' => $task->slug]));
});

it('checks if the task slug attribute updated according updated title attribute', function() {
    $task = Task::factory()->create(['title' => 'New Task']);
    expect($task->slug)->toBe('new-task');
    $this->assertDatabaseHas('tasks', ['slug' => $task->slug]);

    $taskData = [
        'title' => 'Updated Task',
        'id' => $task->id
    ];

    $this->put(action([TaskController::class, 'update'], ['task' => $task->slug]), $taskData);

    $task->refresh();
    expect($task->slug)->toBe('updated-task');
    $this->assertDatabaseHas('tasks', ['slug' => $task->slug]);
});

/* ----------------------------- @destroy method ---------------------------- */
it('checks deletion of task entry by given ID', function () {
    $task = Task::factory()->create(['title' => 'New Task Title']);

    $this->assertDatabaseHas('tasks', ['title' => 'New Task Title']);

    $response = $this->delete(action([TaskController::class, 'destroy'], ['task' => $task->slug]));

    $response->assertRedirect(route('public.index'));
    $this->assertDatabaseMissing('tasks', ['title' => 'New Task Title']);
});
