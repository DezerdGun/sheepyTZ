@extends('layouts.app')

@section('title','Добавить роль')

@section('content')
  <div class="container py-4">
    <h1>Добавить роль</h1>
    <form action="{{ route('admin.roles.store') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label class="form-label">Название</label>
        <input name="name" class="form-control" value="{{ old('name') }}" required>
        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <button class="btn btn-primary">Сохранить</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-link">Отмена</a>
      </div>
    </form>
  </div>
@endsection
