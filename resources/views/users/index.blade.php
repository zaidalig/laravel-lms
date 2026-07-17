@extends('layouts.app')@section('title','Users')@section('page_title','Users')
@section('content')
<div class="d-flex justify-content-between mb-4"><p class="text-muted mb-0">System accounts and roles.</p><a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill">Add User</a></div>
<div class="card filter-card border-0 mb-4"><div class="card-body p-3"><form method="GET" class="row g-2">
<div class="col-md-5"><input name="search" class="form-control" placeholder="Search name or email" value="{{ request('search') }}"></div>
<div class="col-md-3"><select name="role" class="form-select form-select-sm form-select-compact"><option value="">All Roles</option>@foreach(['owner','instructor','student','viewer'] as $r)<option value="{{ $r }}" @selected(request('role')===$r)>{{ ucfirst($r) }}</option>@endforeach</select></div>
<div class="col-md-4 d-flex gap-2"><button class="btn btn-dark w-100">Filter</button>@if(request()->anyFilled(['search','role']))<a href="{{ route('users.index') }}" class="btn btn-outline-secondary w-100">Clear</a>@endif</div>
</form></div></div>
<div class="card card-table border-0"><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th class="text-end">Actions</th></tr></thead><tbody>
@foreach($users as $user)
<tr><td class="fw-semibold">{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ ucfirst($user->role) }}</td><td><span class="badge {{ $user->status==='active'?'bg-success-subtle text-success':'bg-danger-subtle text-danger' }}">{{ ucfirst($user->status) }}</span></td>
<td class="text-end"><span class="table-actions"><a href="{{ route('users.edit',$user) }}" class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit"><i class="fa-solid fa-pen"></i></a>@if($user->id !== auth()->id()) <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="{{ route('users.destroy',$user) }}" data-name="{{ $user->name }}"><i class="fa-solid fa-trash"></i></button>@endif</span></td></tr>
@endforeach
</tbody></table></div><x-table-pagination :paginator="$users" :sorts="['name'=>'Name','email'=>'Email','role'=>'Role','status'=>'Status','created_at'=>'Created']" /></div>
@endsection
