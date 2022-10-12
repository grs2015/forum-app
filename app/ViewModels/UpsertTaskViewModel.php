<?php

namespace App\ViewModels;

use App\Models\Task;
use App\DataTransferObjects\TaskData;

class UpsertTaskViewModel extends ViewModel
{
    public function __construct(
        public readonly ?Task $task = null
    ) {}

    public function task(): ?TaskData
    {
        if (!$this->task) {
            return null;
        }

        return TaskData::from($this->task);
    }
}
