@extends('layouts.app')

@section('title', 'Категории')

@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4">Категории</h1>
      <a href="{{ route('admin.categories.create') }}" class="btn btn-success">Создать</a>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
      <div class="card-body p-0">
        <table class="table table-striped mb-0">
          <thead><tr><th>ID</th><th>Название</th><th class="text-end">Действия</th></tr></thead>
          <tbody>
            @foreach($categories as $cat)
              <tr>
                <td>{{ $cat->id }}</td>
                <td>{{ $cat->name }}</td>
                <td class="text-end">
                  <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-primary">Ред.</a>
                  <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Удалить?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Удал.</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-3">{{ $categories->links() }}</div>
  </div>
@endsection
