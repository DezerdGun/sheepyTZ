@extends('layouts.app')

@section('title','Редактировать водителя')

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <h1 class="h5 mb-3">Редактировать водителя</h1>
      <form action="{{ route('admin.drivers.update', $driver) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
          <label class="form-label">Имя</label>
          <input name="name" class="form-control" value="{{ old('name', $driver->name) }}" required>
          @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Телефон</label>
          <input name="phone" class="form-control" value="{{ old('phone', $driver->phone) }}">
          @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        @include('admin._form_actions', ['cancelUrl' => route('admin.drivers.index')])
      </form>
    </div>
  </div>
@endsection
