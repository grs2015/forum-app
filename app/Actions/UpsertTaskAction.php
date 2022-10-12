<?php

namespace App\Actions;

use App\Models\Task;
use Illuminate\Support\Facades\DB;
use App\DataTransferObjects\TaskData;

class UpsertTaskAction
{
    public static function execute(TaskData $data)
    {
        if (!$data->id) {
            DB::insert('insert into tasks (title, description, slug) values (?, ?, ?)', [$data->title, $data->description, $data->slug]);
            return;
            // $id = DB::select('select id from tasks order by id desc LIMIT 1')[0]->id;
            // $task = DB::select('select * from tasks where id = ?', [$id])[0];
        } else {
            DB::update('update tasks set title = ?, description = ?, slug = ? where id = ?', [$data->title, $data->description, $data->slug, $data->id]);
            return;
        }
    }
}
