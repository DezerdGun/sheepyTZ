@extends('layouts.app')
@section('title','Редактировать модель')
@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <h1 class="h5 mb-3">Редактировать модель</h1>
      @include('admin.car_models._form', ['action' => route('admin.car-models.update', $carModel), 'method' => 'PUT', 'model' => $carModel])
    </div>
  </div>
@endsection
