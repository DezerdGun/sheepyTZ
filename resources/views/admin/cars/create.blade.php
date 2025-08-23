@extends('layouts.app')

@section('title','Создать автомобиль')

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <h1 class="h5 mb-3">Добавить автомобиль</h1>
      @include('admin.cars._form', ['action' => route('admin.cars.store'), 'method' => 'POST', 'car' => null])
    </div>
  </div>
@endsection
