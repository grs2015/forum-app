<?php

use App\Models\Task;
use App\Http\Controllers\TaskController;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Inertia\Testing\AssertableInertia as Assert;

/* ------------------------------ @index method ----------------------------- */
it('renders task list page with Inertia', function() {
    $this->withoutExceptionHandling();

    $tasks = Task::factory()->count(2)->state(new Sequence(['title' => 'First task'], ['title' => 'Second task']))->create();

    $response = $this->get(action([TaskController::class, 'index']));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Public/Index')
        ->has('model', fn (Assert $page) => $page
            ->has('tasks', fn (Assert $page) => $page
                ->has('data', 2)
                ->etc())
            ->etc()) );
});
