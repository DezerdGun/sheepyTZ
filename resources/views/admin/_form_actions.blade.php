<div class="mb-3 d-flex gap-2">
  <button class="btn btn-primary">Сохранить</button>
  <a href="{{ $cancelUrl ?? url()->previous() }}" class="btn btn-outline-secondary">Отмена</a>
</div>
