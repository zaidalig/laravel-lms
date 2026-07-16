@extends('layouts.app')@section('title','Activity')@section('page_title','Activity Logs')
@section('content')
<div class="card filter-card border-0 mb-4"><div class="card-body p-3"><form method="GET" class="row g-2"><div class="col-md-4"><select name="action" class="form-select"><option value="">All Actions</option>@foreach(['created','updated','deleted'] as $a)<option value="{{ $a }}" @selected(request('action')===$a)>{{ ucfirst($a) }}</option>@endforeach</select></div><div class="col-md-4 d-flex gap-2"><button class="btn btn-dark w-100">Filter</button>@if(request()->filled('action'))<a href="{{ route('activity.index') }}" class="btn btn-outline-secondary w-100">Clear</a>@endif</div></form></div></div>
<div class="card card-table border-0"><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>Action</th><th>Description</th><th>User</th><th>When</th></tr></thead><tbody>
@forelse($logs as $log)
<tr><td><span class="badge bg-light text-dark border">{{ $log->action }}</span></td><td>{{ $log->description }}</td><td>{{ $log->user?->name ?? 'System' }}</td><td>{{ $log->created_at->format('M d, Y H:i') }}</td></tr>
@empty<tr><td colspan="4" class="text-center py-4 text-muted">No activity yet.</td></tr>@endforelse
</tbody></table></div>@if($logs->hasPages())<div class="card-footer bg-white">{{ $logs->links() }}</div>@endif</div>
@endsection
