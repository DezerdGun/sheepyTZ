(function(){
  function toIsoLocal(datetimeLocal) {
    if (!datetimeLocal) return '';
    return new Date(datetimeLocal).toISOString();
  }

  async function fetchCars(paramsObj){
    const params = new URLSearchParams(paramsObj);
    const res = await fetch('/admin/available-cars/data?'+params.toString(), {credentials:'same-origin'});
    if (!res.ok) throw new Error('HTTP '+res.status);
    return res.json();
  }

  function renderRows(data){
    if (!Array.isArray(data) || data.length === 0) return '<tr><td colspan="3">Нет доступных автомобилей</td></tr>';
    return data.map(c => {
      const modelName = c.car_model?.name || (c.carModel?.name || '—');
      const cat = c.car_model?.category?.name || c.carModel?.category?.name || '—';
      const driver = c.driver?.name || '—';
      return `<tr><td>${c.plate_number}</td><td>${modelName} / ${cat}</td><td>${driver}</td></tr>`;
    }).join('');
  }

  document.addEventListener('DOMContentLoaded', function(){
    const btn = document.getElementById('btn-refresh');
    const tableBody = document.querySelector('#cars-table tbody');
    const modelInput = document.getElementById('filter-model');
    const categoryInput = document.getElementById('filter-category');
    const startInput = document.getElementById('start-time');
    const endInput = document.getElementById('end-time');
  const modelOptions = Array.from(modelInput.querySelectorAll('option'));

    async function onFetch(){
  tableBody.innerHTML = '<tr><td colspan="3">Загрузка... <span class="spinner-inline"></span></td></tr>';
  btn.disabled = true;
      const params = {};
      if (modelInput.value.trim()) params.model = modelInput.value.trim();
      if (categoryInput.value.trim()) params.category = categoryInput.value.trim();
      if (startInput.value) params.start_time = toIsoLocal(startInput.value);
      if (endInput.value) params.end_time = toIsoLocal(endInput.value);
      try {
        const data = await fetchCars(params);
        tableBody.innerHTML = renderRows(data);
      } catch (err) {
        tableBody.innerHTML = '<tr><td colspan="3">Ошибка: '+err.message+'</td></tr>';
      } finally {
        btn.disabled = false;
      }
    }
    btn.addEventListener('click', onFetch);
    categoryInput.addEventListener('change', function(){
      const cat = categoryInput.value;
      modelInput.innerHTML = '';
      modelInput.appendChild(new Option('Все модели', ''));
      modelOptions.forEach(opt => {
        const optCat = opt.dataset.category || '';
        if (!cat || optCat === cat) {
          modelInput.appendChild(opt.cloneNode(true));
        }
      });
    });
  });
})();
