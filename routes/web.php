<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CourseCategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MyLearningController;
use App\Http\Controllers\PublicCourseController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public frontend
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courses', [PublicCourseController::class, 'index'])->name('public.courses');
Route::get('/courses/{course:slug}', [PublicCourseController::class, 'show'])->name('public.courses.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware(['auth', 'active.user'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/courses/{course:slug}/enroll', [PublicCourseController::class, 'enroll'])->name('public.courses.enroll');

    Route::get('/my/courses', [MyLearningController::class, 'index'])->name('my.courses');
    Route::get('/my/courses/{enrollment}', [MyLearningController::class, 'show'])->name('my.courses.show');
    Route::post('/my/courses/{enrollment}/lessons/{lesson}/complete', [MyLearningController::class, 'complete'])->name('my.lessons.complete');
    Route::get('/my/courses/{enrollment}/quizzes/{quiz}', [MyLearningController::class, 'quiz'])->name('my.quizzes.show');
    Route::post('/my/courses/{enrollment}/quizzes/{quiz}/submit', [MyLearningController::class, 'submitQuiz'])->name('my.quizzes.submit');

    Route::middleware('can:manage-courses')->prefix('admin')->group(function () {
        Route::resource('categories', CourseCategoryController::class)->except(['show'])->parameters(['categories' => 'category']);
        Route::resource('courses', CourseController::class);
        Route::get('courses/{course}/progress-export', [CourseController::class, 'progressExport'])->name('courses.progress-export');
        Route::post('courses/{course}/lessons', [LessonController::class, 'store'])->name('lessons.store');
        Route::get('lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
        Route::put('lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
        Route::delete('lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
        Route::post('courses/{course}/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
        Route::get('quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
        Route::put('quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
        Route::delete('quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
        Route::post('quizzes/{quiz}/questions', [QuizQuestionController::class, 'store'])->name('quiz-questions.store');
        Route::delete('quiz-questions/{question}', [QuizQuestionController::class, 'destroy'])->name('quiz-questions.destroy');
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity.index');
    });

    Route::middleware('can:manage-users')->prefix('admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});
