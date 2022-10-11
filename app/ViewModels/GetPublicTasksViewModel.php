<?php

namespace App\ViewModels;

use Illuminate\Http\Request;
use App\ViewModels\ViewModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\DataTransferObjects\TaskData;
use Illuminate\Pagination\LengthAwarePaginator;

class GetPublicTasksViewModel extends ViewModel
{
    public function __construct(
        public Request $request
    ) {}

    public static function tasks(): LengthAwarePaginator
    {
        $items = Collection::make(DB::select('select * from tasks'))
            ->map(function($item) { return TaskData::from($item); });

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;

        $results = $items->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $items->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        return $paginated->appends(request()->query());
    }
}
