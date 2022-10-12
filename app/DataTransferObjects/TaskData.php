<?php

namespace App\DataTransferObjects;

use DateTime;
use App\Models\Task;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class TaskData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $title,
        public readonly ?string $description,
        public readonly ?string $slug,
    ) {}

    public static function fromModel(Task $task): self
    {
        return self::from([
            ...$task->toArray(),
        ]);
    }

    public static function fromRequest(Request $request): self
    {
        return self::from([
            ...$request->all(),
            'slug' => Str::slug($request->title, '-')
        ]);
    }

    public static function rules(Request $request): array
    {
        return [
            'title' => ['required', 'string', Rule::unique('tasks')->ignore($request->id)],
            'description' => ['nullable', 'sometimes', 'string'],
            'slug' => ['nullable', 'sometimes', 'string']
        ];
    }
}
