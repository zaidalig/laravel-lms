@extends('layouts.app')@section('title','Courses')@section('page_title','Courses')
@section('content')
<div class="d-flex justify-content-between mb-4"><p class="text-muted mb-0">Course catalog administration.</p><a href="{{ route('courses.create') }}" class="btn btn-primary rounded-pill">Add Course</a></div>
<div class="card filter-card border-0 mb-4"><div class="card-body p-3"><form method="GET" class="row g-2">
<div class="col-md-3"><input name="search" class="form-control" placeholder="Search title" value="{{ request('search') }}"></div>
<div class="col-md-2"><select name="category_id" class="form-select"><option value="">All Categories</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category_id')==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
<div class="col-md-2"><select name="level" class="form-select"><option value="">All Levels</option>@foreach(['beginner','intermediate','advanced'] as $l)<option value="{{ $l }}" @selected(request('level')===$l)>{{ ucfirst($l) }}</option>@endforeach</select></div>
<div class="col-md-2"><select name="status" class="form-select"><option value="">All Status</option>@foreach(['draft','published','archived'] as $s)<option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
<div class="col-md-3 d-flex gap-2"><button class="btn btn-dark w-100">Filter</button>@if(request()->anyFilled(['search','category_id','level','status']))<a href="{{ route('courses.index') }}" class="btn btn-outline-secondary w-100">Clear</a>@endif</div>
</form></div></div>
<div class="card card-table border-0"><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>Title</th><th>Category</th><th>Instructor</th><th>Level</th><th>Price</th><th>Lessons</th><th>Enrolled</th><th>Status</th><th class="text-end">Actions</th></tr></thead><tbody>
@forelse($courses as $course)
<tr><td><a href="{{ route('courses.show',$course) }}" class="fw-bold text-decoration-none">{{ $course->title }}</a></td><td>{{ $course->category?->name ?? '-' }}</td><td>{{ $course->instructor?->name ?? '-' }}</td><td>{{ ucfirst($course->level) }}</td><td>{{ $course->price > 0 ? '$'.number_format($course->price,2) : 'Free' }}</td><td>{{ $course->lessons_count }}</td><td>{{ $course->enrollments_count }}</td>
<td><span class="badge {{ $course->status==='published'?'bg-success-subtle text-success':($course->status==='draft'?'bg-warning-subtle text-warning':'bg-secondary-subtle text-secondary') }}">{{ ucfirst($course->status) }}</span></td>
<td class="text-end"><a href="{{ route('courses.edit',$course) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a> <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="{{ route('courses.destroy',$course) }}" data-name="{{ $course->title }}"><i class="fa-solid fa-trash"></i></button></td></tr>
@empty<tr><td colspan="9" class="text-center py-4 text-muted">No courses found.</td></tr>@endforelse
</tbody></table></div>@if($courses->hasPages())<div class="card-footer bg-white">{{ $courses->links() }}</div>@endif</div>
@endsection
