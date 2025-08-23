@extends('layouts.app')

@section('title', 'Доступные служебные автомобили')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-available-cars.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-" crossorigin="anonymous">
@endpush

@section('content')
  <div class="container">
    <h1 class="mb-4">Доступные служебные автомобили</h1>

    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <label class="form-label">Модель</label>
        <select id="filter-model" class="form-select">
          <option value="">Все модели</option>
          @foreach($carModels as $m)
            <option value="{{ $m->name }}" data-category="{{ $m->category->name ?? '' }}">{{ $m->name }} ({{ $m->category->name ?? '-' }})</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Категория</label>
        <select id="filter-category" class="form-select">
          <option value="">Все категории</option>
          @foreach($categories as $c)
            <option value="{{ $c->name }}">{{ $c->name }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="controls" style="margin-top:12px">
      <div>
        <label class="form-label">Время начала</label>
        <input id="start-time" class="form-control" type="datetime-local">
      </div>
      <div>
        <label class="form-label">Время окончания</label>
        <input id="end-time" class="form-control" type="datetime-local">
      </div>
    </div>

    <div class="mb-4 mt-3">
      <button id="btn-refresh" class="btn btn-primary">Показать доступные</button>
    </div>

    <div class="table-responsive">
      <table id="cars-table" class="table table-striped table-hover" aria-live="polite">
        <thead>
          <tr><th>Госномер</th><th>Модель / Категория</th><th>Водитель</th></tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="" crossorigin="anonymous"></script>
    <script src="{{ asset('js/admin-available-cars.js') }}"></script>
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="" crossorigin="anonymous"></script>
  <script src="{{ asset('js/admin-available-cars.js') }}"></script>
@endpush
