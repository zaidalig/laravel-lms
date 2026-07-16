<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Platform Owner', 'email' => 'owner@example.com', 'password' => 'password', 'role' => 'owner', 'status' => 'active'],
            ['name' => 'Amira Instructor', 'email' => 'instructor@example.com', 'password' => 'password', 'role' => 'instructor', 'status' => 'active'],
            ['name' => 'Sami Student', 'email' => 'student@example.com', 'password' => 'password', 'role' => 'student', 'status' => 'active'],
            ['name' => 'Zara Student', 'email' => 'student2@example.com', 'password' => 'password', 'role' => 'student', 'status' => 'active'],
            ['name' => 'Read Only', 'email' => 'viewer@example.com', 'password' => 'password', 'role' => 'viewer', 'status' => 'inactive'],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $categories = [
            ['name' => 'Web Development', 'description' => 'Frontend and backend web development'],
            ['name' => 'Design', 'description' => 'UI, UX, and graphic design'],
            ['name' => 'Business', 'description' => 'Entrepreneurship, marketing, and management'],
            ['name' => 'Data Science', 'description' => 'Data analysis, ML, and statistics'],
        ];

        foreach ($categories as $category) {
            CourseCategory::create($category + ['slug' => Str::slug($category['name']), 'status' => 'active']);
        }

        $courses = [
            ['title' => 'Laravel From Scratch', 'course_category_id' => 1, 'level' => 'beginner', 'price' => 49.00, 'duration_hours' => 12, 'status' => 'published', 'description' => 'Build modern web applications with Laravel. Routing, Eloquent, Blade, authentication, and deployment — everything you need to ship your first app.'],
            ['title' => 'JavaScript Essentials', 'course_category_id' => 1, 'level' => 'beginner', 'price' => 0, 'duration_hours' => 8, 'status' => 'published', 'description' => 'Master the fundamentals of JavaScript: variables, functions, the DOM, events, and asynchronous programming with promises and async/await.'],
            ['title' => 'UI Design Fundamentals', 'course_category_id' => 2, 'level' => 'beginner', 'price' => 29.00, 'duration_hours' => 6, 'status' => 'published', 'description' => 'Learn typography, color theory, spacing, and layout. Design clean, usable interfaces that people love.'],
            ['title' => 'Digital Marketing 101', 'course_category_id' => 3, 'level' => 'intermediate', 'price' => 39.00, 'duration_hours' => 7, 'status' => 'published', 'description' => 'SEO, content marketing, email campaigns, and paid ads. A practical playbook for growing an online audience.'],
            ['title' => 'Python for Data Analysis', 'course_category_id' => 4, 'level' => 'intermediate', 'price' => 59.00, 'duration_hours' => 14, 'status' => 'published', 'description' => 'Analyze real datasets with pandas, NumPy, and matplotlib. Clean data, explore it, and communicate insights.'],
            ['title' => 'Advanced Laravel Patterns', 'course_category_id' => 1, 'level' => 'advanced', 'price' => 79.00, 'duration_hours' => 10, 'status' => 'draft', 'description' => 'Actions, service classes, decorators, and testing strategies for large Laravel codebases. Coming soon.'],
        ];

        foreach ($courses as $course) {
            Course::create($course + ['slug' => Str::slug($course['title']), 'instructor_id' => 2]);
        }

        $lessonSets = [
            1 => ['Installing Laravel & Tooling', 'Routing and Controllers', 'Blade Templates', 'Eloquent Basics', 'Forms and Validation', 'Deploying Your App'],
            2 => ['Variables and Types', 'Functions and Scope', 'Working with the DOM', 'Events', 'Promises and Async/Await'],
            3 => ['Design Principles', 'Typography', 'Color Theory', 'Layout and Spacing'],
        ];

        foreach ($lessonSets as $courseId => $titles) {
            foreach ($titles as $i => $title) {
                Lesson::create([
                    'course_id' => $courseId,
                    'title' => $title,
                    'content' => "In this lesson we cover {$title}. Follow along with the examples and complete the practice exercise at the end before moving on to the next lesson.",
                    'video_url' => 'https://videos.example.com/lesson-'.$courseId.'-'.($i + 1),
                    'position' => $i + 1,
                    'duration_minutes' => 15 + ($i * 5),
                ]);
            }
        }

        // student@example.com: one course ~50% complete, one just started
        $e1 = Enrollment::create(['course_id' => 1, 'user_id' => 3, 'enrolled_at' => today()->subDays(14), 'status' => 'active']);
        foreach (Lesson::where('course_id', 1)->orderBy('position')->limit(3)->get() as $lesson) {
            LessonCompletion::create(['enrollment_id' => $e1->id, 'lesson_id' => $lesson->id, 'completed_at' => now()->subDays(10)]);
        }

        Enrollment::create(['course_id' => 2, 'user_id' => 3, 'enrolled_at' => today()->subDays(2), 'status' => 'active']);

        // student2@example.com: one fully completed course
        $e3 = Enrollment::create(['course_id' => 3, 'user_id' => 4, 'enrolled_at' => today()->subDays(30), 'status' => 'completed']);
        foreach (Lesson::where('course_id', 3)->get() as $lesson) {
            LessonCompletion::create(['enrollment_id' => $e3->id, 'lesson_id' => $lesson->id, 'completed_at' => now()->subDays(20)]);
        }
    }
}
