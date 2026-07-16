@extends('layouts.app')@section('title','Add Category')@section('page_title','Add Category')
@section('content')<div class="card p-4 border-0 shadow-sm"><form method="POST" action="{{ route('categories.store') }}">@csrf @include('categories._form')<button class="btn btn-primary">Save</button> <a href="{{ route('categories.index') }}" class="btn btn-light">Cancel</a></form></div>@endsection
