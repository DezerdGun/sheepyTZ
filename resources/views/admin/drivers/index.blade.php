@extends('layouts.app')

@section('title','Водители')

@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>Водители</h1>
      <a href="{{ route('admin.drivers.create') }}" class="btn btn-success">Добавить водителя</a>
    </div>

    <table class="table table-striped">
      <thead>
        <tr><th>ID</th><th>Имя</th><th>Телефон</th><th></th></tr>
      </thead>
      <tbody>
        @foreach($items as $it)
          <tr>
            <td>{{ $it->id }}</td>
            <td>{{ $it->name }}</td>
            <td>{{ $it->phone ?? '-' }}</td>
            <td class="text-end">
              <a href="{{ route('admin.drivers.edit', $it) }}" class="btn btn-sm btn-primary">Ред.</a>
              <form action="{{ route('admin.drivers.destroy', $it) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Удалить?')">
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
