@extends('layouts.app')@section('title','My Learning')@section('page_title','My Learning')
@section('content')
<div class="d-flex justify-content-between mb-4"><p class="text-muted mb-0">Your enrolled courses and progress.</p><a href="{{ route('public.courses') }}" class="btn btn-primary rounded-pill">Browse Catalog</a></div>
<div class="row g-4">
@forelse($enrollments as $enrollment)
@php($progress = $enrollment->progressPercent())
<div class="col-md-6 col-xl-4"><div class="card border-0 shadow-sm h-100"><div class="card-body">
<div class="mb-2"><span class="badge bg-primary-subtle text-primary">{{ $enrollment->course?->category?->name ?? 'General' }}</span> <span class="badge {{ $enrollment->status==='completed'?'bg-success-subtle text-success':'bg-light text-dark border' }}">{{ ucfirst($enrollment->status) }}</span></div>
<h5 class="fw-bold">{{ $enrollment->course?->title }}</h5>
<p class="text-muted small mb-2">Enrolled {{ $enrollment->enrolled_at->format('M d, Y') }}</p>
<div class="progress mb-2" style="height:8px;"><div class="progress-bar {{ $progress>=100?'bg-success':'' }}" style="width: {{ $progress }}%"></div></div>
<div class="d-flex justify-content-between align-items-center"><span class="text-muted small">{{ $progress }}% complete</span><a href="{{ route('my.courses.show', $enrollment) }}" class="btn btn-sm btn-primary rounded-pill">{{ $progress >= 100 ? 'Review' : 'Continue' }}</a></div>
</div></div></div>
@empty
<div class="col-12"><div class="card border-0 shadow-sm p-5 text-center text-muted">You are not enrolled in any course yet. <a href="{{ route('public.courses') }}">Browse the catalog</a> to get started.</div></div>
@endforelse
</div>
@endsection
