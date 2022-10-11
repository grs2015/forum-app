<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\LaravelData\WithData;
use App\DataTransferObjects\TaskData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function(Task $task) {
            if (!$task->slug) {
                $task->slug = Str::slug($task->title, '-');
            }
        });
    }
}
