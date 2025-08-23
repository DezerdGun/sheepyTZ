<form action="{{ $action }}" method="POST">
  @csrf
  @if(in_array(strtoupper($method ?? 'POST'), ['PUT','PATCH','DELETE']))
    @method($method)
  @endif

  <div class="mb-3">
    <label class="form-label">Госномер</label>
    <input name="plate_number" class="form-control" value="{{ old('plate_number', $car->plate_number ?? '') }}">
    @error('plate_number')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Модель</label>
    <select name="car_model_id" class="form-select">
      @foreach($models as $m)
        <option value="{{ $m->id }}" {{ (old('car_model_id', $car->car_model_id ?? '') == $m->id) ? 'selected' : '' }}>{{ $m->name }}</option>
      @endforeach
    </select>
    @error('car_model_id')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Водитель</label>
    <select name="driver_id" class="form-select" required>
      @foreach($drivers as $d)
        <option value="{{ $d->id }}" {{ (old('driver_id', $car->driver_id ?? '') == $d->id) ? 'selected' : '' }}>{{ $d->name }}</option>
      @endforeach
    </select>
    @error('driver_id')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  @include('admin._form_actions', ['cancelUrl' => route('admin.cars.index')])
</form>
