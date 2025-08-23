<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $items = Role::orderBy('name')->paginate(20);
        return view('admin.roles.index', compact('items'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        Role::create($data);
        return redirect()->route('admin.roles.index')->with('success','Роль создана');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        $role->update($data);
        return redirect()->route('admin.roles.index')->with('success','Роль обновлена');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success','Роль удалена');
    }
}
