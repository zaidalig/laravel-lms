@extends('layouts.app')@section('title',$enrollment->course->title)@section('page_title','Learning')
@section('content')
@php($progress = $enrollment->progressPercent())
<div class="card border-0 shadow-sm mb-4"><div class="card-body p-4">
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
<div>
<h4 class="fw-bold mb-1">{{ $enrollment->course->title }}</h4>
<span class="text-muted small">{{ count($completedIds) }} of {{ $lessons->count() }} lessons completed</span>
</div>
<span class="badge {{ $enrollment->status==='completed'?'bg-success-subtle text-success':'bg-primary-subtle text-primary' }} px-3 py-2">{{ ucfirst($enrollment->status) }}</span>
</div>
<div class="progress mt-3" style="height:10px;"><div class="progress-bar {{ $progress>=100?'bg-success':'' }}" style="width: {{ $progress }}%"></div></div>
</div></div>
<div class="row g-4">
<div class="col-lg-4">
<div class="card border-0 shadow-sm lesson-list"><div class="card-header bg-white fw-bold">Lessons</div>
<ul class="list-group list-group-flush">
@forelse($lessons as $lesson)
<li class="list-group-item d-flex justify-content-between align-items-center {{ $current && $current->id === $lesson->id ? 'active' : '' }}">
<a href="{{ route('my.courses.show', ['enrollment' => $enrollment, 'lesson' => $lesson->id]) }}" class="text-decoration-none text-reset flex-grow-1">{{ $loop->iteration }}. {{ $lesson->title }}</a>
@if(in_array($lesson->id, $completedIds))<i class="fa-solid fa-circle-check text-success"></i>@else<span class="text-muted small">{{ $lesson->duration_minutes }}m</span>@endif
</li>
@empty<li class="list-group-item text-center text-muted py-4">No lessons in this course yet.</li>@endforelse
</ul></div>
</div>
<div class="col-lg-8">
@if($current)
<div class="card border-0 shadow-sm"><div class="card-body p-4">
<div class="d-flex justify-content-between align-items-start mb-3">
<h5 class="fw-bold mb-0">{{ $current->title }}</h5>
<span class="text-muted small"><i class="fa-regular fa-clock me-1"></i>{{ $current->duration_minutes }} min</span>
</div>
@if($current->video_url || $current->attachment_path)<p class="d-flex gap-2">@if($current->video_url)<a href="{{ $current->video_url }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill"><i class="fa-solid fa-video me-1"></i>Watch Video</a>@endif @if($current->attachment_path)<a href="{{ media_url($current->attachment_path) }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill"><i class="fa-solid fa-file-pdf me-1"></i>Download PDF</a>@endif</p>@endif
<p class="mb-4" style="white-space: pre-line;">{{ $current->content }}</p>
@if(in_array($current->id, $completedIds))
<span class="badge bg-success-subtle text-success px-3 py-2"><i class="fa-solid fa-circle-check me-1"></i>Completed</span>
@else
<form method="POST" action="{{ route('my.lessons.complete', ['enrollment' => $enrollment, 'lesson' => $current]) }}">@csrf<button class="btn btn-success rounded-pill"><i class="fa-solid fa-check me-1"></i>Mark as Complete</button></form>
@endif
</div></div>
@else
<div class="card border-0 shadow-sm p-5 text-center text-muted">Select a lesson to start learning.</div>
@endif
</div>
</div>
@endsection
