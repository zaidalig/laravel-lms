@extends('layouts.app')@section('title',$course->title)@section('page_title','Course Details')
@section('content')
<div class="card border-0 shadow-sm mb-4"><div class="card-body p-4">
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
<div>
<div class="mb-2"><span class="badge bg-primary-subtle text-primary">{{ $course->category?->name ?? 'General' }}</span> <span class="badge bg-light text-dark border">{{ ucfirst($course->level) }}</span> <span class="badge {{ $course->status==='published'?'bg-success-subtle text-success':($course->status==='draft'?'bg-warning-subtle text-warning':'bg-secondary-subtle text-secondary') }}">{{ ucfirst($course->status) }}</span></div>
<h3 class="fw-bold mb-1">{{ $course->title }}</h3>
<p class="text-muted mb-2">{{ $course->description }}</p>
<div class="d-flex flex-wrap gap-3 text-muted small">
<span><i class="fa-solid fa-chalkboard-user me-1"></i>{{ $course->instructor?->name ?? 'Unassigned' }}</span>
<span><i class="fa-solid fa-dollar-sign me-1"></i>{{ $course->price > 0 ? number_format($course->price,2) : 'Free' }}</span>
<span><i class="fa-regular fa-clock me-1"></i>{{ $course->duration_hours }}h</span>
<span><i class="fa-solid fa-users me-1"></i>{{ $course->enrollments_count }} enrolled</span>
<span><i class="fa-solid fa-link me-1"></i>{{ $course->slug }}</span>
</div>
</div>
<div class="d-flex gap-2">
@if($course->isPublished())<a href="{{ route('public.courses.show',$course->slug) }}" class="btn btn-outline-secondary rounded-pill">View Public Page</a>@endif
<a href="{{ route('courses.edit',$course) }}" class="btn btn-primary rounded-pill">Edit</a>
</div>
</div>
</div></div>
<div class="row g-4">
<div class="col-lg-7">
<div class="card card-table border-0"><div class="card-header bg-white fw-bold">Lessons ({{ $course->lessons->count() }})</div><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>#</th><th>Title</th><th>Duration</th><th class="text-end">Actions</th></tr></thead><tbody>
@forelse($course->lessons as $lesson)
<tr><td><span class="badge bg-light text-dark border">{{ $lesson->position }}</span></td><td class="fw-semibold">{{ $lesson->title }}@if($lesson->video_url) <i class="fa-solid fa-video text-muted small"></i>@endif</td><td>{{ $lesson->duration_minutes }} min</td>
<td class="text-end">@if($lesson->attachment_path)<a href="{{ Storage::url($lesson->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Download PDF"><i class="fa-solid fa-file-pdf"></i></a> @endif<a href="{{ route('lessons.edit',$lesson) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a> <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="{{ route('lessons.destroy',$lesson) }}" data-name="{{ $lesson->title }}"><i class="fa-solid fa-trash"></i></button></td></tr>
@empty<tr><td colspan="4" class="text-center py-4 text-muted">No lessons yet. Add the first one.</td></tr>@endforelse
</tbody></table></div></div>
<div class="card border-0 shadow-sm mt-4"><div class="card-header bg-white fw-bold">Add Lesson</div><div class="card-body">
<form method="POST" action="{{ route('lessons.store',$course) }}" enctype="multipart/form-data">@csrf
<div class="row">
<div class="col-md-8 mb-3"><label class="form-label">Title</label><input name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>@error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-2 mb-3"><label class="form-label">Position</label><input type="number" min="1" name="position" class="form-control" value="{{ old('position', $course->lessons->max('position') + 1) }}" required></div>
<div class="col-md-2 mb-3"><label class="form-label">Minutes</label><input type="number" min="1" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', 10) }}" required></div>
</div>
<div class="mb-3"><label class="form-label">Content</label><textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" required>{{ old('content') }}</textarea>@error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="mb-3"><label class="form-label">Video URL <span class="text-muted small">(optional)</span></label><input name="video_url" class="form-control @error('video_url') is-invalid @enderror" value="{{ old('video_url') }}">@error('video_url')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="mb-3"><label class="form-label">PDF attachment <span class="text-muted small">(optional)</span></label><input type="file" name="attachment" accept="application/pdf" class="form-control @error('attachment') is-invalid @enderror">@error('attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<button class="btn btn-primary">Add Lesson</button>
</form>
</div></div>
</div>
<div class="col-lg-5">
<div class="card card-table border-0"><div class="card-header bg-white fw-bold">Enrolled Students ({{ $course->enrollments_count }})</div><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>Student</th><th>Enrolled</th><th>Status</th><th>Progress</th></tr></thead><tbody>
@forelse($enrollments as $e)
<tr><td class="fw-semibold">{{ $e->user?->name ?? '-' }}</td><td>{{ $e->enrolled_at->format('M d, Y') }}</td><td><span class="badge {{ $e->status==='completed'?'bg-success-subtle text-success':'bg-primary-subtle text-primary' }}">{{ ucfirst($e->status) }}</span></td><td>{{ $e->progressPercent() }}%</td></tr>
@empty<tr><td colspan="4" class="text-center py-4 text-muted">No students enrolled yet.</td></tr>@endforelse
</tbody></table></div></div>
</div>
</div>
@endsection
