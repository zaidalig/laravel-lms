@extends('layouts.public')
@section('title','Courses')
@section('content')
<section class="py-5 bg-light border-bottom">
    <div class="container">
        <h1 class="fw-bold mb-1">Course Catalog</h1>
        <p class="text-muted mb-0">{{ $courses->total() }} published {{ Str::plural('course', $courses->total()) }} to explore.</p>
    </div>
</section>
<section class="py-4">
    <div class="container">
        <div class="card filter-card border-0 mb-4"><div class="card-body p-3"><form method="GET" class="row g-2">
            <div class="col-md-4"><input name="search" class="form-control" placeholder="Search course title" value="{{ request('search') }}"></div>
            <div class="col-md-3"><select name="category_id" class="form-select"><option value="">All Categories</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category_id')==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
            <div class="col-md-2"><select name="level" class="form-select"><option value="">All Levels</option>@foreach(['beginner','intermediate','advanced'] as $l)<option value="{{ $l }}" @selected(request('level')===$l)>{{ ucfirst($l) }}</option>@endforeach</select></div>
            <div class="col-md-3 d-flex gap-2"><button class="btn btn-dark w-100">Filter</button>@if(request()->anyFilled(['search','category_id','level']))<a href="{{ route('public.courses') }}" class="btn btn-outline-secondary w-100">Clear</a>@endif</div>
        </form></div></div>
        <div class="row g-4">
            @forelse($courses as $course)
            <div class="col-md-6 col-lg-4">@include('public._course_card')</div>
            @empty
            <div class="col-12 text-center text-muted py-5">No courses match your filters.</div>
            @endforelse
        </div>
        @if($courses->hasPages())<div class="mt-4">{{ $courses->links() }}</div>@endif
    </div>
</section>
@endsection
