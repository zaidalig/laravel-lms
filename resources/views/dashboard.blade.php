@extends('layouts.app')
@section('title','Dashboard')@section('page_title', auth()->user()->canManageCourses() ? 'LMS Dashboard' : 'My Learning')
@section('content')
@if(auth()->user()->canManageCourses())
<div class="row g-4 mb-4">
@foreach([['Courses',$stats['courses'],'chalkboard','primary'],['Published',$stats['published'],'circle-check','success'],['Students Enrolled',$stats['students'],'user-graduate','info'],['Lessons',$stats['lessons'],'list-check','warning']] as $s)
<div class="col-md-6 col-xl-3"><div class="card stat-card card-{{ $s[3] }} p-3"><div class="d-flex justify-content-between"><div><div class="text-muted small">{{ $s[0] }}</div><h3 class="fw-bold mb-0">{{ $s[1] }}</h3></div><div class="card-icon bg-{{ $s[3] }}-subtle text-{{ $s[3] }}"><i class="fa-solid fa-{{ $s[2] }}"></i></div></div></div></div>
@endforeach
</div>
<div class="row g-4">
<div class="col-lg-6"><div class="card card-table border-0"><div class="card-header bg-white fw-bold">Recent Enrollments</div><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>Student</th><th>Course</th><th>Status</th><th>When</th></tr></thead><tbody>@forelse($recentEnrollments as $e)<tr><td>{{ $e->user?->name ?? '-' }}</td><td>{{ $e->course?->title ?? '-' }}</td><td><span class="badge {{ $e->status==='completed'?'bg-success-subtle text-success':'bg-primary-subtle text-primary' }}">{{ ucfirst($e->status) }}</span></td><td class="text-muted small">{{ $e->created_at->diffForHumans() }}</td></tr>@empty<tr><td colspan="4" class="text-center py-3 text-muted">No enrollments yet.</td></tr>@endforelse</tbody></table></div></div></div>
<div class="col-lg-6"><div class="card card-table border-0"><div class="card-header bg-white fw-bold">Latest Activity</div><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>Action</th><th>Description</th><th>User</th></tr></thead><tbody>@forelse($recentLogs as $log)<tr><td><span class="badge bg-light text-dark border">{{ $log->action }}</span></td><td>{{ $log->description }}</td><td>{{ $log->user?->name ?? 'System' }}</td></tr>@empty<tr><td colspan="3" class="text-center py-3 text-muted">No activity yet.</td></tr>@endforelse</tbody></table></div></div></div>
</div>
@else
<div class="d-flex justify-content-between mb-4"><p class="text-muted mb-0">Your enrolled courses and progress.</p><a href="{{ route('public.courses') }}" class="btn btn-primary rounded-pill">Browse Catalog</a></div>
<div class="row g-4">
@forelse($enrollments as $enrollment)
@php($progress = $enrollment->progressPercent())
<div class="col-md-6 col-xl-4"><div class="card border-0 shadow-sm h-100"><div class="card-body">
<div class="mb-2"><span class="badge bg-primary-subtle text-primary">{{ $enrollment->course?->category?->name ?? 'General' }}</span> <span class="badge {{ $enrollment->status==='completed'?'bg-success-subtle text-success':'bg-light text-dark border' }}">{{ ucfirst($enrollment->status) }}</span></div>
<h5 class="fw-bold">{{ $enrollment->course?->title }}</h5>
<div class="progress mb-2" style="height:8px;"><div class="progress-bar {{ $progress>=100?'bg-success':'' }}" style="width: {{ $progress }}%"></div></div>
<div class="d-flex justify-content-between align-items-center"><span class="text-muted small">{{ $progress }}% complete</span><a href="{{ route('my.courses.show', $enrollment) }}" class="btn btn-sm btn-primary rounded-pill">Continue</a></div>
</div></div></div>
@empty
<div class="col-12"><div class="card border-0 shadow-sm p-5 text-center text-muted">You are not enrolled in any course yet. <a href="{{ route('public.courses') }}">Browse the catalog</a> to get started.</div></div>
@endforelse
</div>
@endif
@endsection
