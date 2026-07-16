<div class="card course-card border-0 shadow-sm">
    <div class="course-thumb"><i class="fa-solid fa-book-open"></i></div>
    <div class="card-body d-flex flex-column">
        <div class="mb-2">
            <span class="badge bg-primary-subtle text-primary">{{ $course->category?->name ?? 'General' }}</span>
            <span class="badge bg-light text-dark border">{{ ucfirst($course->level) }}</span>
        </div>
        <h5 class="fw-bold"><a href="{{ route('public.courses.show', $course->slug) }}" class="text-decoration-none text-dark">{{ $course->title }}</a></h5>
        <p class="text-muted small flex-grow-1">{{ Str::limit($course->description, 90) }}</p>
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted small"><i class="fa-solid fa-list-check me-1"></i>{{ $course->lessons_count }} lessons</span>
            <span class="fw-bold text-primary">{{ $course->price > 0 ? '$'.number_format($course->price, 2) : 'Free' }}</span>
        </div>
    </div>
</div>
