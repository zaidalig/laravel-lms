<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //

    /**
     * @param  array<int, string>  $allowedSorts
     * @return array{0: int, 1: string, 2: string}
     */
    protected function listQueryParams(\Illuminate\Http\Request $request, array $allowedSorts, string $defaultSort = 'created_at', string $defaultDirection = 'desc'): array
    {
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $sort = (string) $request->input('sort', $defaultSort);
        if (! in_array($sort, $allowedSorts, true)) {
            $sort = $defaultSort;
        }

        $direction = $request->input('direction', $defaultDirection) === 'asc' ? 'asc' : 'desc';

        return [$perPage, $sort, $direction];
    }

}
