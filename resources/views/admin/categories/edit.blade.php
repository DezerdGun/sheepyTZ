@extends('layouts.app')

@section('title', 'Редактировать категорию')

@section('content')
  <div class="container">
    <h1 class="h4 mb-3">Редактировать категорию</h1>
    @include('admin.categories._form', ['action' => route('admin.categories.update', $category), 'method' => 'PUT', 'category' => $category])
  </div>
@endsection
