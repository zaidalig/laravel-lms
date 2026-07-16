@extends('layouts.public')
@section('title', $course->title)
@section('content')
<section class="py-5 bg-light border-bottom">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="mb-2">
                    <span class="badge bg-primary-subtle text-primary">{{ $course->category?->name ?? 'General' }}</span>
                    <span class="badge bg-light text-dark border">{{ ucfirst($course->level) }}</span>
                </div>
                <h1 class="fw-bold">{{ $course->title }}</h1>
                <p class="text-muted mb-2">{{ $course->description }}</p>
                <div class="d-flex flex-wrap gap-3 text-muted small">
                    <span><i class="fa-solid fa-chalkboard-user me-1"></i>{{ $course->instructor?->name ?? 'LearnHub Team' }}</span>
                    <span><i class="fa-solid fa-list-check me-1"></i>{{ $course->lessons->count() }} lessons</span>
                    <span><i class="fa-regular fa-clock me-1"></i>{{ $course->duration_hours }}h total</span>
                    <span><i class="fa-solid fa-users me-1"></i>{{ $course->enrollments_count }} enrolled</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 text-center">
                    <div class="display-6 fw-bold text-primary mb-3">{{ $course->price > 0 ? '$'.number_format($course->price, 2) : 'Free' }}</div>
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg rounded-pill">Login to Enroll</a>
                    @else
                        @if($enrollment)
                            <a href="{{ route('my.courses.show', $enrollment) }}" class="btn btn-success btn-lg rounded-pill"><i class="fa-solid fa-circle-play me-1"></i>Continue Learning</a>
                        @else
                            <form method="POST" action="{{ route('public.courses.enroll', $course->slug) }}">@csrf<button class="btn btn-primary btn-lg rounded-pill w-100"><i class="fa-solid fa-user-plus me-1"></i>Enroll Now</button></form>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-4">Course Content</h2>
        <div class="card border-0 shadow-sm">
            <ul class="list-group list-group-flush">
                @forelse($course->lessons as $lesson)
                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                    <span><span class="badge bg-light text-dark border me-2">{{ $loop->iteration }}</span>{{ $lesson->title }}</span>
                    <span class="text-muted small"><i class="fa-regular fa-clock me-1"></i>{{ $lesson->duration_minutes }} min</span>
                </li>
                @empty
                <li class="list-group-item text-center text-muted py-4">Lesson list coming soon.</li>
                @endforelse
            </ul>
        </div>
    </div>
</section>
@endsection
