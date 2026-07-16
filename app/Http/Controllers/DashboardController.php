<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->canManageCourses()) {
            $stats = [
                'courses' => Course::count(),
                'published' => Course::where('status', 'published')->count(),
                'students' => Enrollment::distinct('user_id')->count('user_id'),
                'lessons' => Lesson::count(),
            ];

            $recentEnrollments = Enrollment::with(['course', 'user'])->latest()->limit(6)->get();
            $recentLogs = ActivityLog::with('user')->latest()->limit(8)->get();

            return view('dashboard', compact('stats', 'recentEnrollments', 'recentLogs'));
        }

        $enrollments = $user->enrollments()->with(['course.category'])->latest()->get();

        return view('dashboard', compact('enrollments'));
    }
}
