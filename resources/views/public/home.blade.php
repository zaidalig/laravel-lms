@extends('layouts.public')
@section('title','Learn Online')
@section('content')
<section class="hero">
    <div class="container text-center">
        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-3">Online courses for everyone</span>
        <h1 class="display-4 mb-3">Learn new skills with <span class="text-primary">LearnHub</span></h1>
        <p class="lead text-muted mb-4 mx-auto" style="max-width: 620px;">Browse expert-led courses in development, design, business, and more. Enroll for free or at a fair price and track your progress lesson by lesson.</p>
        <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('public.courses') }}" class="btn btn-primary btn-lg rounded-pill px-4">Browse Courses</a>
            @guest<a href="{{ route('login') }}" class="btn btn-outline-dark btn-lg rounded-pill px-4">Login</a>@endguest
        </div>
    </div>
</section>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Latest Courses</h2>
            <a href="{{ route('public.courses') }}" class="text-decoration-none fw-semibold">View all <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            @forelse($courses as $course)
            <div class="col-md-6 col-lg-4">@include('public._course_card')</div>
            @empty
            <div class="col-12 text-center text-muted py-5">No courses published yet. Check back soon!</div>
            @endforelse
        </div>
    </div>
</section>
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold mb-4">Browse by Category</h2>
        <div class="d-flex flex-wrap gap-2">
            @foreach($categories as $category)
            <a href="{{ route('public.courses', ['category_id' => $category->id]) }}" class="category-chip"><i class="fa-solid fa-tag text-primary"></i>{{ $category->name }} <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $category->courses_count }}</span></a>
            @endforeach
        </div>
    </div>
</section>
@endsection
