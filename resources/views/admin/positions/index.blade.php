@extends('layouts.app')

@section('title','Должности')

@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>Должности</h1>
      <a href="{{ route('admin.positions.create') }}" class="btn btn-success">Добавить</a>
    </div>

    <table class="table table-striped">
      <thead><tr><th>ID</th><th>Название</th><th></th></tr></thead>
      <tbody>
        @foreach($items as $it)
          <tr>
            <td>{{ $it->id }}</td>
            <td>{{ $it->name }}</td>
            <td class="text-end">
              <a href="{{ route('admin.positions.edit', $it) }}" class="btn btn-sm btn-primary">Ред.</a>
              <form action="{{ route('admin.positions.destroy', $it) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Удалить?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">Удал.</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    {{ $items->links() }}
  </div>
@endsection
