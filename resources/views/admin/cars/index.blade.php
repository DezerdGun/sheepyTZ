@extends('layouts.app')

@section('title', 'Автомобили')

@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>Автомобили</h1>
      <a href="{{ route('admin.cars.create') }}" class="btn btn-success">Добавить автомобиль</a>
    </div>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Госномер</th>
          <th>Модель</th>
          <th>Категория</th>
          <th>Водитель</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($cars as $car)
          <tr>
            <td>{{ $car->id }}</td>
            <td>{{ $car->plate_number }}</td>
            <td>{{ optional($car->carModel)->name }}</td>
            <td>{{ optional($car->carModel->category)->name }}</td>
            <td>{{ optional($car->driver)->name }}</td>
            <td class="text-end">
              <a href="{{ route('admin.cars.edit', $car) }}" class="btn btn-sm btn-primary">Ред.</a>
              <form action="{{ route('admin.cars.destroy', $car) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Удалить?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">Удал.</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    {{ $cars->links() }}
  </div>
@endsection
