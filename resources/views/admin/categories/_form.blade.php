<form action="{{ $action }}" method="POST">
  @csrf
  @if(in_array(strtoupper($method ?? 'POST'), ['PUT','PATCH','DELETE']))
    @method($method)
  @endif

  <div class="mb-3">
    <label class="form-label">Название</label>
    <input name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}">
    @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="mb-3">
    <button class="btn btn-primary">Сохранить</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-link">Отмена</a>
  </div>
</form>
