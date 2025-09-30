<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMenuController extends Controller
{
    // --- CRUD Categorías ---
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);
        Category::create($request->only('name', 'description'));
        return redirect()->route('admin.menu.index');
    }

    public function editCategory(Category $category)
    {
        $categories = Category::with('menuItems')->orderBy('name')->get();
        return view('admin.menu', compact('categories', 'category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);
        $category->update($request->only('name', 'description'));
        return redirect()->route('admin.menu.index');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.menu.index');
    }

    // --- CRUD Platos ---
    public function storeItem(Request $request)
    {
        $this->authorizeItem('create');
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);
        $data = $request->only('category_id', 'name', 'description', 'price');
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('menu', 'public');
        }
    // $data['is_out'] = false; // No guardar is_out en la base de datos, solo visual
        MenuItem::create($data);
        return redirect()->route('admin.menu.index');
    }

    public function editItem(MenuItem $item)
    {
        $categories = Category::with('menuItems')->orderBy('name')->get();
        return view('admin.menu', compact('categories', 'item'));
    }

    public function updateItem(Request $request, MenuItem $item)
    {
        $this->authorizeItem('edit');
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);
        $data = $request->only('category_id', 'name', 'description', 'price');
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('menu', 'public');
        }
        $item->update($data);
        return redirect()->route('admin.menu.index');
    }

    public function destroyItem(MenuItem $item)
    {
        $this->authorizeItem('delete');
        $item->delete();
        return redirect()->route('admin.menu.index');
    }

    public function toggleItemOut(MenuItem $item)
    {
        $this->authorizeItem('toggle', $item);
        $item->is_out = !$item->is_out;
        $item->save();
        return redirect()->route('admin.menu.index');
    }

    // --- Restricciones por rol ---
    private function authorizeItem($action, $item = null)
    {
        $role = Auth::user()->roles_id;
        if ($action === 'create' || $action === 'edit') {
            if (!in_array($role, [3,4])) abort(403);
        }
        if ($action === 'delete') {
            if ($role !== 4) abort(403);
        }
        if ($action === 'toggle') {
            if (!in_array($role, [2,3,4])) abort(403);
        }
    }

    public function index()
    {
        // Solo permitir acceso a roles distintos de cliente (roles_id > 1)
        if (Auth::user()->roles_id == 1) {
            abort(403, 'No autorizado.');
        }
        $categories = Category::with('menuItems')->orderBy('name')->get();
        return view('admin.menu', compact('categories'));
    }
}
