@extends('layouts.app')

@section('title','Редактировать должность')

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <h1 class="h5 mb-3">Редактировать должность</h1>
      <form action="{{ route('admin.positions.update', $position) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
          <label class="form-label">Название</label>
          <input name="name" class="form-control" value="{{ old('name', $position->name) }}" required>
          @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        @include('admin._form_actions', ['cancelUrl' => route('admin.positions.index')])
      </form>
    </div>
  </div>
@endsection
