@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <h1>Редактировать автомобиль</h1>
    @include('admin.cars._form', ['action' => route('admin.cars.update', $car), 'method' => 'PUT', 'car' => $car])
  </div>
@endsection
@extends('layouts.app')
@section('title','Редактировать автомобиль')
@section('content')
<div class="container">
  <h1 class="h4 mb-3">Редактировать автомобиль</h1>
  @include('admin.cars._form', ['action' => route('admin.cars.update', $car), 'method' => 'PUT', 'car' => $car])
</div>
@endsection
