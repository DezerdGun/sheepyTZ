@extends('layouts.app')

@section('title','Редактировать должность')

@section('content')
  <div class="container py-4">
    <h1>Редактировать должность</h1>
    <form action="{{ route('admin.positions.update', $position) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label class="form-label">Название</label>
        <input name="name" class="form-control" value="{{ old('name', $position->name) }}" required>
        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <button class="btn btn-primary">Сохранить</button>
        <a href="{{ route('admin.positions.index') }}" class="btn btn-link">Отмена</a>
      </div>
    </form>
  </div>
@endsection
