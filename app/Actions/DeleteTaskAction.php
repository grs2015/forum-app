<?php

namespace App\Actions;

use App\Models\Task;
use Illuminate\Support\Facades\DB;

class DeleteTaskAction
{
    public static function execute(Task $task)
    {
        DB::delete('delete from tasks where id = ?', [$task->id]);
    }
}
