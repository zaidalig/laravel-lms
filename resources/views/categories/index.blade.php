@extends('layouts.app')@section('title','Categories')@section('page_title','Course Categories')
@section('content')
<div class="d-flex justify-content-between mb-4"><p class="text-muted mb-0">Organize courses by topic.</p><a href="{{ route('categories.create') }}" class="btn btn-primary rounded-pill">Add Category</a></div>
<div class="card filter-card border-0 mb-4"><div class="card-body p-3"><form method="GET" class="row g-2">
<div class="col-md-5"><input name="search" class="form-control" placeholder="Search name" value="{{ request('search') }}"></div>
<div class="col-md-3"><select name="status" class="form-select form-select-sm form-select-compact"><option value="">All Status</option><option value="active" @selected(request('status')==='active')>Active</option><option value="inactive" @selected(request('status')==='inactive')>Inactive</option></select></div>
<div class="col-md-4 d-flex gap-2"><button class="btn btn-dark w-100">Filter</button>@if(request()->anyFilled(['search','status']))<a href="{{ route('categories.index') }}" class="btn btn-outline-secondary w-100">Clear</a>@endif</div>
</form></div></div>
<div class="card card-table border-0"><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>Name</th><th>Slug</th><th>Courses</th><th>Status</th><th class="text-end">Actions</th></tr></thead><tbody>
@forelse($categories as $c)
<tr><td class="fw-bold">{{ $c->name }}</td><td class="text-muted">{{ $c->slug }}</td><td>{{ $c->courses_count }}</td><td><span class="badge {{ $c->status==='active'?'bg-success-subtle text-success':'bg-danger-subtle text-danger' }}">{{ ucfirst($c->status) }}</span></td>
<td class="text-end"><span class="table-actions"><a href="{{ route('categories.edit',$c) }}" class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit"><i class="fa-solid fa-pen"></i></a> <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="{{ route('categories.destroy',$c) }}" data-name="{{ $c->name }}"><i class="fa-solid fa-trash"></i></button></span></td></tr>
@empty<tr><td colspan="5" class="text-center py-4 text-muted">No categories found.</td></tr>@endforelse
</tbody></table></div><x-table-pagination :paginator="$categories" :sorts="['name'=>'Name','created_at'=>'Created']" /></div>
@endsection
