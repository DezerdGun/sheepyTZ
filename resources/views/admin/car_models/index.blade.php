@extends('layouts.app')
@section('title','Модели')
@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4">Модели</h1>
    <a href="{{ route('admin.car-models.create') }}" class="btn btn-success">Создать</a>
  </div>

  <table class="table">
    <thead><tr><th>ID</th><th>Название</th><th>Категория</th><th></th></tr></thead>
    <tbody>
      @foreach($items as $it)
      <tr>
        <td>{{ $it->id }}</td>
        <td>{{ $it->name }}</td>
        <td>{{ $it->category->name ?? '-' }}</td>
        <td class="text-end">
          <a href="{{ route('admin.car-models.edit', $it) }}" class="btn btn-sm btn-primary">Ред.</a>
          <form action="{{ route('admin.car-models.destroy', $it) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Удалить?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Удалить</button></form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{ $items->links() }}
</div>
@endsection
