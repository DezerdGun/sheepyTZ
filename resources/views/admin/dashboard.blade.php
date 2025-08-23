@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <h1>Админ-панель</h1>
    <p>Быстрые ссылки для управления справочниками и автомобилями.</p>

    <div class="list-group">
      <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action">Категории</a>
      <a href="{{ route('admin.car-models.index') }}" class="list-group-item list-group-item-action">Модели автомобилей</a>
      <a href="{{ route('admin.cars.index') }}" class="list-group-item list-group-item-action">Автомобили</a>
      <a href="{{ route('admin.drivers.index') }}" class="list-group-item list-group-item-action">Водители</a>
      <a href="{{ route('admin.positions.index') }}" class="list-group-item list-group-item-action">Должности</a>
      <a href="{{ route('admin.roles.index') }}" class="list-group-item list-group-item-action">Роли</a>
      <a href="{{ url('/admin/available-cars') }}" class="list-group-item list-group-item-action">Доступные автомобили (по времени)</a>
    </div>
  </div>
@endsection
