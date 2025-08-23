<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Driver;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::with(['carModel','driver'])->orderBy('plate_number')->paginate(20);
        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        $models = CarModel::orderBy('name')->get();
        $drivers = Driver::orderBy('name')->get();
        return view('admin.cars.create', compact('models','drivers'));
    }

    public function store(Request $request)
    {
        if ($request->filled('driver_id') === false) {
            $request->merge(['driver_id' => null]);
        }

        $data = $request->validate([
            'plate_number' => 'required|string|unique:cars,plate_number',
            'car_model_id' => 'required|exists:car_models,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        Car::create($data);
        return redirect()->route('admin.cars.index')->with('success','Автомобиль создан');
    }

    public function edit(Car $car)
    {
        $models = CarModel::orderBy('name')->get();
        $drivers = Driver::orderBy('name')->get();
        return view('admin.cars.edit', compact('car','models','drivers'));
    }

    public function update(Request $request, Car $car)
    {
        if ($request->filled('driver_id') === false) {
            $request->merge(['driver_id' => null]);
        }

        $data = $request->validate([
            'plate_number' => 'required|string|unique:cars,plate_number,'.$car->id,
            'car_model_id' => 'required|exists:car_models,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $car->update($data);
        return redirect()->route('admin.cars.index')->with('success','Автомобиль обновлён');
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('admin.cars.index')->with('success','Автомобиль удалён');
    }
}
