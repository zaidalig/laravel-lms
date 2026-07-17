@extends('layouts.app')@section('title','Edit Quiz')@section('page_title','Edit Quiz')
@section('content')
<p class="text-muted">Course: <a href="{{ route('courses.show',$quiz->course) }}" class="fw-semibold text-decoration-none">{{ $quiz->course->title }}</a></p>
<div class="card p-4 border-0 shadow-sm mb-4"><form method="POST" action="{{ route('quizzes.update',$quiz) }}">@csrf @method('PUT')
<div class="row">
<div class="col-md-8 mb-3"><label class="form-label">Title</label><input name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $quiz->title) }}" required>@error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-4 mb-3"><label class="form-label">Pass Score % <span class="text-muted small">(optional)</span></label><input type="number" min="0" max="100" name="pass_score" class="form-control @error('pass_score') is-invalid @enderror" value="{{ old('pass_score', $quiz->pass_score) }}" placeholder="Default 70">@error('pass_score')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
</div>
<button class="btn btn-primary">Update Quiz</button> <a href="{{ route('courses.show',$quiz->course) }}" class="btn btn-light">Back</a>
</form></div>
<div class="card card-table border-0 mb-4"><div class="card-header bg-white fw-bold">Questions ({{ $quiz->questions->count() }})</div><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>Question</th><th>Correct</th><th class="text-end">Actions</th></tr></thead><tbody>
@forelse($quiz->questions as $question)
<tr><td class="fw-semibold">{{ $question->question }}</td><td><span class="badge bg-light text-dark border">{{ strtoupper($question->correct_option) }}</span></td>
<td class="text-end"><button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="{{ route('quiz-questions.destroy',$question) }}" data-name="this question"><i class="fa-solid fa-trash"></i></button></td></tr>
@empty<tr><td colspan="3" class="text-center py-4 text-muted">No questions yet. Add one below.</td></tr>@endforelse
</tbody></table></div></div>
<div class="card border-0 shadow-sm"><div class="card-header bg-white fw-bold">Add Question</div><div class="card-body">
<form method="POST" action="{{ route('quiz-questions.store',$quiz) }}">@csrf
<div class="mb-3"><label class="form-label">Question</label><textarea name="question" class="form-control @error('question') is-invalid @enderror" rows="2" required>{{ old('question') }}</textarea>@error('question')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="row">
<div class="col-md-6 mb-3"><label class="form-label">Option A</label><input name="option_a" class="form-control @error('option_a') is-invalid @enderror" value="{{ old('option_a') }}" required>@error('option_a')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-6 mb-3"><label class="form-label">Option B</label><input name="option_b" class="form-control @error('option_b') is-invalid @enderror" value="{{ old('option_b') }}" required>@error('option_b')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-6 mb-3"><label class="form-label">Option C</label><input name="option_c" class="form-control @error('option_c') is-invalid @enderror" value="{{ old('option_c') }}" required>@error('option_c')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-6 mb-3"><label class="form-label">Option D</label><input name="option_d" class="form-control @error('option_d') is-invalid @enderror" value="{{ old('option_d') }}" required>@error('option_d')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
</div>
<div class="mb-3"><label class="form-label">Correct Option</label><select name="correct_option" class="form-select @error('correct_option') is-invalid @enderror" required><option value="a" @selected(old('correct_option')==='a')>A</option><option value="b" @selected(old('correct_option')==='b')>B</option><option value="c" @selected(old('correct_option')==='c')>C</option><option value="d" @selected(old('correct_option')==='d')>D</option></select>@error('correct_option')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<button class="btn btn-primary">Add Question</button>
</form>
</div></div>
@endsection
