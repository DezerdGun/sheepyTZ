<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Car;
use App\Models\Category;
use App\Models\CarModel;


class AvailableCarsController extends Controller
{
    public function index(Request $request)
    {
    // Load categories and car models for selects
    $categories = Category::orderBy('name')->get();
    $carModels = CarModel::with('category')->orderBy('name')->get();

    return view('admin.available_cars', compact('categories', 'carModels'));
    }

    // Return JSON data for the admin UI using session-authenticated user
    public function available(Request $request)
    {
    $user = Auth::user();

    // If unauthenticated, don't restrict by user's position categories (allow all)
    $allowedCategoryIds = $user?->position?->categories->pluck('id')->toArray() ?? [];

        $query = Car::query()
            ->whereHas('carModel.category', function ($q) use ($allowedCategoryIds) {
                if (!empty($allowedCategoryIds)) {
                    $q->whereIn('id', $allowedCategoryIds);
                }
            })
            ->whereDoesntHave('bookings', function ($q) use ($request) {
                if ($request->filled('start_time') && $request->filled('end_time')) {
                    $start = $request->start_time;
                    $end = $request->end_time;
                    $q->where(function ($qq) use ($start, $end) {
                        $qq->where('start_time', '<', $end)
                           ->where('end_time', '>', $start);
                    });
                }
            });

        if ($request->filled('model')) {
            $query->whereHas('carModel', function ($q) use ($request) {
                $q->where('name', 'ILIKE', "%{$request->model}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('carModel.category', fn($q) => $q->where('name', $request->category));
        }

        return response()->json($query->with(['driver', 'carModel.category'])->get());
    }
}
