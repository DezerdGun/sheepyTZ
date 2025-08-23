@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <h1 class="dashboard-title">Админ-панель</h1>
    <p class="text-muted mb-4">Быстрые ссылки для управления справочниками и автомобилями.</p>

    <div class="dashboard-grid">
      <a href="{{ route('admin.categories.index') }}" class="dashboard-card">Категории</a>
      <a href="{{ route('admin.car-models.index') }}" class="dashboard-card">Модели автомобилей</a>
      <a href="{{ route('admin.cars.index') }}" class="dashboard-card">Автомобили</a>
      <a href="{{ route('admin.drivers.index') }}" class="dashboard-card">Водители</a>
      <a href="{{ route('admin.positions.index') }}" class="dashboard-card">Должности</a>
      <a href="{{ route('admin.roles.index') }}" class="dashboard-card">Роли</a>
      <a href="{{ url('/admin/available-cars') }}" class="dashboard-card">Доступные автомобили</a>
    </div>
  </div>
@endsection
