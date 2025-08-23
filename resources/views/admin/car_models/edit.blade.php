@extends('layouts.app')
@section('title','Редактировать модель')
@section('content')
<div class="container">
  <h1 class="h4 mb-3">Редактировать модель</h1>
  @include('admin.car_models._form', ['action' => route('admin.car-models.update', $carModel), 'method' => 'PUT', 'model' => $carModel])
</div>
@endsection
