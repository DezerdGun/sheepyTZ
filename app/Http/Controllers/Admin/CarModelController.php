<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarModel;
use App\Models\Category;

class CarModelController extends Controller
{
    public function index()
    {
        $items = CarModel::with('category')->orderBy('name')->paginate(20);
        return view('admin.car_models.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.car_models.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);
        CarModel::create($data);
        return redirect()->route('admin.car-models.index')->with('success', 'Модель создана');
    }

    public function edit(CarModel $carModel)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.car_models.edit', [
            'carModel' => $carModel,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, CarModel $carModel)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);
        $carModel->update($data);
        return redirect()->route('admin.car-models.index')->with('success', 'Модель обновлена');
    }

    public function destroy(CarModel $carModel)
    {
        $carModel->delete();
        return redirect()->route('admin.car-models.index')->with('success', 'Модель удалена');
    }
}
