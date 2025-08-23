@extends('layouts.app')

@section('title', 'Редактировать категорию')

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <h1 class="h5 mb-3">Редактировать категорию</h1>
      @include('admin.categories._form', ['action' => route('admin.categories.update', $category), 'method' => 'PUT', 'category' => $category])
    </div>
  </div>
@endsection
