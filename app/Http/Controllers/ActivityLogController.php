<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');


        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($activityQuery) use ($search) {
                $activityQuery->where('description', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($userQuery) => $userQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        [$perPage, $sort, $direction] = $this->listQueryParams($request, ['action', 'created_at'], 'created_at');
        $logs = $query->orderBy($sort, $direction)->paginate($perPage)->withQueryString();

        return view('activity.index', compact('logs'));
    }
}
