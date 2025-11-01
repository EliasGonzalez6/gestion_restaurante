<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $categories = Category::with('menuItems')->orderBy('id')->get();
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

    // Reordenar categorías (drag and drop)
    public function reorderCategories(Request $request)
    {
        // Solo Gerentes y Supervisores pueden reordenar
        if (!in_array(Auth::user()->roles_id, [3, 4])) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $order = $request->input('order'); // Array de IDs en el nuevo orden
        
        if (!is_array($order) || empty($order)) {
            return response()->json(['success' => false, 'message' => 'Orden inválido'], 400);
        }

        try {
            // Obtener todas las categorías actuales ordenadas por ID
            $categories = Category::orderBy('id')->get();
            $currentIds = $categories->pluck('id')->toArray();
            
            // Si el orden no cambió, no hacer nada
            if ($currentIds === $order) {
                return response()->json(['success' => true, 'message' => 'Sin cambios']);
            }

            DB::transaction(function () use ($order) {
                // Desactivar temporalmente las foreign key constraints
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                
                // Usar IDs temporales muy altos para evitar conflictos
                $tempIdBase = 900000;
                
                // Paso 1: Mover todas las categorías a IDs temporales
                foreach ($order as $index => $categoryId) {
                    $tempId = $tempIdBase + $index;
                    DB::statement('UPDATE categories SET id = ? WHERE id = ?', [$tempId, $categoryId]);
                    // También actualizar la FK en menu_items
                    DB::statement('UPDATE menu_items SET category_id = ? WHERE category_id = ?', [$tempId, $categoryId]);
                }
                
                // Paso 2: Mover de IDs temporales a IDs finales (1, 2, 3, ...)
                foreach ($order as $index => $categoryId) {
                    $tempId = $tempIdBase + $index;
                    $finalId = $index + 1;
                    DB::statement('UPDATE categories SET id = ? WHERE id = ?', [$finalId, $tempId]);
                    DB::statement('UPDATE menu_items SET category_id = ? WHERE category_id = ?', [$finalId, $tempId]);
                }
                
                // Reactivar las foreign key constraints
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            });

            return response()->json(['success' => true, 'message' => 'Orden actualizado']);
        } catch (\Exception $e) {
            // Asegurarse de reactivar las foreign keys en caso de error
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            Log::error('Error reordenando categorías: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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
        $categories = Category::with('menuItems')->orderBy('id')->get();
        return view('admin.menu', [
            'categories' => $categories,
            'editItem' => $item
        ]);
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
        // Redirigir a la página anterior si la petición viene de otra vista
        $referer = request()->headers->get('referer');
        $welcomeUrl = route('welcome');
        $adminUrl = route('admin.menu.index');
        if ($referer) {
            if (str_starts_with($referer, $welcomeUrl)) {
                return redirect($referer);
            }
            if (str_starts_with($referer, $adminUrl)) {
                return redirect($referer);
            }
        }
        // Fallback: si no hay referer, ir al panel admin
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
        $categories = Category::with('menuItems')->orderBy('id')->get();
        return view('admin.menu', compact('categories'));
    }
}
