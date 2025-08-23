@extends('layouts.app')

@section('title','Редактировать роль')

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <h1 class="h5 mb-3">Редактировать роль</h1>
      <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
          <label class="form-label">Название</label>
          <input name="name" class="form-control" value="{{ old('name', $role->name) }}" required>
          @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        @include('admin._form_actions', ['cancelUrl' => route('admin.roles.index')])
      </form>
    </div>
  </div>
@endsection
