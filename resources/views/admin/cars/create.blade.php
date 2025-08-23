@extends('layouts.app')

@section('title','Создать автомобиль')

@section('content')
  <div class="container py-4">
    <h1>Добавить автомобиль</h1>
    @include('admin.cars._form', ['action' => route('admin.cars.store'), 'method' => 'POST', 'car' => null])
  </div>
@endsection
