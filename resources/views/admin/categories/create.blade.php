@extends('layouts.app')

@section('title', 'Создать категорию')

@section('content')
  <div class="container">
    <h1 class="h4 mb-3">Создать категорию</h1>
    @include('admin.categories._form', ['action' => route('admin.categories.store'), 'method' => 'POST', 'category' => null])
  </div>
@endsection
