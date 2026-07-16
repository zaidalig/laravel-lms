<div class="row">
<div class="col-md-8 mb-3"><label class="form-label">Title</label><input name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $course->title ?? '') }}" required>@error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-4 mb-3"><label class="form-label">Category</label><select name="course_category_id" class="form-select"><option value="">None</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('course_category_id', $course->course_category_id ?? '')==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
</div>
<div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $course->description ?? '') }}</textarea>@error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="row">
<div class="col-md-3 mb-3"><label class="form-label">Instructor</label><select name="instructor_id" class="form-select"><option value="">Unassigned</option>@foreach($instructors as $i)<option value="{{ $i->id }}" @selected(old('instructor_id', $course->instructor_id ?? '')==$i->id)>{{ $i->name }}</option>@endforeach</select></div>
<div class="col-md-3 mb-3"><label class="form-label">Level</label><select name="level" class="form-select">@foreach(['beginner','intermediate','advanced'] as $l)<option value="{{ $l }}" @selected(old('level', $course->level ?? 'beginner')===$l)>{{ ucfirst($l) }}</option>@endforeach</select></div>
<div class="col-md-2 mb-3"><label class="form-label">Price ($)</label><input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $course->price ?? '0.00') }}" required></div>
<div class="col-md-2 mb-3"><label class="form-label">Duration (hours)</label><input type="number" min="1" name="duration_hours" class="form-control" value="{{ old('duration_hours', $course->duration_hours ?? 1) }}" required></div>
<div class="col-md-2 mb-3"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['draft','published','archived'] as $s)<option value="{{ $s }}" @selected(old('status', $course->status ?? 'draft')===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
</div>
