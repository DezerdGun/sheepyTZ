<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
    public function index()
    {
        $items = Position::orderBy('name')->paginate(20);
        return view('admin.positions.index', compact('items'));
    }

    public function create()
    {
        return view('admin.positions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        Position::create($data);
        return redirect()->route('admin.positions.index')->with('success','Должность создана');
    }

    public function edit(Position $position)
    {
        return view('admin.positions.edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        $position->update($data);
        return redirect()->route('admin.positions.index')->with('success','Должность обновлена');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('admin.positions.index')->with('success','Должность удалена');
    }
}
