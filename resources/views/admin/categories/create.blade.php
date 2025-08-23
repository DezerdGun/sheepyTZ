@extends('layouts.app')

@section('title', 'Создать категорию')

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <h1 class="h5 mb-3">Создать категорию</h1>
      @include('admin.categories._form', ['action' => route('admin.categories.store'), 'method' => 'POST', 'category' => null])
    </div>
  </div>
@endsection
