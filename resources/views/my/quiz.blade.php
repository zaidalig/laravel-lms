@extends('layouts.app')@section('title',$quiz->title)@section('page_title','Quiz')
@section('content')
<p class="text-muted mb-3"><a href="{{ route('my.courses.show',$enrollment) }}" class="text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i>{{ $enrollment->course->title }}</a></p>
<div class="card border-0 shadow-sm"><div class="card-body p-4">
<h4 class="fw-bold mb-1">{{ $quiz->title }}</h4>
<p class="text-muted small mb-4">{{ $quiz->questions->count() }} questions · Pass score: {{ $quiz->requiredPassScore() }}%</p>
@if($attempt)
<div class="alert alert-{{ $attempt->passed ? 'success' : 'warning' }} mb-0">
<strong>Submitted.</strong> Score: {{ $attempt->score }}% — {{ $attempt->passed ? 'Passed' : 'Did not pass' }}.
</div>
@else
<form method="POST" action="{{ route('my.quizzes.submit', ['enrollment' => $enrollment, 'quiz' => $quiz]) }}">@csrf
@foreach($quiz->questions as $question)
<div class="mb-4 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
<p class="fw-semibold mb-2">{{ $loop->iteration }}. {{ $question->question }}</p>
@foreach(['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d] as $key => $label)
<div class="form-check mb-1"><input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_{{ $key }}" value="{{ $key }}" required><label class="form-check-label" for="q{{ $question->id }}_{{ $key }}">{{ strtoupper($key) }}. {{ $label }}</label></div>
@endforeach
</div>
@endforeach
@if($quiz->questions->isNotEmpty())<button class="btn btn-primary rounded-pill">Submit Quiz</button>@else<p class="text-muted mb-0">No questions available yet.</p>@endif
</form>
@endif
</div></div>
@endsection
