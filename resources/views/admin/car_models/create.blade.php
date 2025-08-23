@extends('layouts.app')
@section('title','Создать модель')
@section('content')
<div class="container">
  <h1 class="h4 mb-3">Создать модель</h1>
  @include('admin.car_models._form', ['action' => route('admin.car-models.store'), 'method' => 'POST', 'model' => null])
</div>
@endsection
