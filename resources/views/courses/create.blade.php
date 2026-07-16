@extends('layouts.app')@section('title','Add Course')@section('page_title','Add Course')
@section('content')<div class="card p-4 border-0 shadow-sm"><form method="POST" action="{{ route('courses.store') }}">@csrf @include('courses._form')<button class="btn btn-primary">Save</button> <a href="{{ route('courses.index') }}" class="btn btn-light">Cancel</a></form></div>@endsection
