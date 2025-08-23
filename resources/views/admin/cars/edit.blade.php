@extends('layouts.app')

@section('title','Редактировать автомобиль')

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <h1 class="h5 mb-3">Редактировать автомобиль</h1>
      @include('admin.cars._form', ['action' => route('admin.cars.update', $car), 'method' => 'PUT', 'car' => $car])
    </div>
  </div>
@endsection
