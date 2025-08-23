@extends('layouts.app')

@section('title','Добавить водителя')

@section('content')
  <div class="container py-4">
    <h1>Добавить водителя</h1>
    <form action="{{ route('admin.drivers.store') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label class="form-label">Имя</label>
        <input name="name" class="form-control" value="{{ old('name') }}" required>
        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Телефон</label>
        <input name="phone" class="form-control" value="{{ old('phone') }}">
        @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <button class="btn btn-primary">Сохранить</button>
        <a href="{{ route('admin.drivers.index') }}" class="btn btn-link">Отмена</a>
      </div>
    </form>
  </div>
@endsection
