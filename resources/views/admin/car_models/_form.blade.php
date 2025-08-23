<form action="{{ $action }}" method="POST">
  @csrf
  @if(in_array(strtoupper($method ?? 'POST'), ['PUT','PATCH','DELETE']))
    @method($method)
  @endif

  <div class="mb-3">
    <label class="form-label">Название</label>
    <input name="name" class="form-control" value="{{ old('name', $model->name ?? '') }}">
    @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Категория</label>
    <select name="category_id" class="form-select">
      <option value="">--</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" {{ (old('category_id', $model->category_id ?? '') == $c->id) ? 'selected' : '' }}>{{ $c->name }}</option>
      @endforeach
    </select>
    @error('category_id')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  @include('admin._form_actions', ['cancelUrl' => route('admin.car-models.index')])
</form>
