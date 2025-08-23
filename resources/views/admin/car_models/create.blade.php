@extends('layouts.app')
@section('title','Создать модель')
@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <h1 class="h5 mb-3">Создать модель</h1>
      @include('admin.car_models._form', ['action' => route('admin.car-models.store'), 'method' => 'POST', 'model' => null])
    </div>
  </div>
@endsection
