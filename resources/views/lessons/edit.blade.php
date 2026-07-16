@extends('layouts.app')@section('title','Edit Lesson')@section('page_title','Edit Lesson')
@section('content')
<p class="text-muted">Course: <a href="{{ route('courses.show',$lesson->course) }}" class="fw-semibold text-decoration-none">{{ $lesson->course->title }}</a></p>
<div class="card p-4 border-0 shadow-sm"><form method="POST" action="{{ route('lessons.update',$lesson) }}">@csrf @method('PUT')
<div class="row">
<div class="col-md-8 mb-3"><label class="form-label">Title</label><input name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $lesson->title) }}" required>@error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-2 mb-3"><label class="form-label">Position</label><input type="number" min="1" name="position" class="form-control" value="{{ old('position', $lesson->position) }}" required></div>
<div class="col-md-2 mb-3"><label class="form-label">Minutes</label><input type="number" min="1" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', $lesson->duration_minutes) }}" required></div>
</div>
<div class="mb-3"><label class="form-label">Content</label><textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content', $lesson->content) }}</textarea>@error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="mb-3"><label class="form-label">Video URL <span class="text-muted small">(optional)</span></label><input name="video_url" class="form-control @error('video_url') is-invalid @enderror" value="{{ old('video_url', $lesson->video_url) }}">@error('video_url')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<button class="btn btn-primary">Update</button> <a href="{{ route('courses.show',$lesson->course) }}" class="btn btn-light">Cancel</a>
</form></div>
@endsection
