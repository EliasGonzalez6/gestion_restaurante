# Documentación: Reordenar Categorías

## Objetivo
Permitir a usuarios con rol Supervisor (3) o Gerente (4) cambiar el orden de las categorías del menú mediante drag & drop en el panel de administración.

---
## 1. Frontend
- Archivo: `resources/views/admin/menu.blade.php`
- Librería usada: [SortableJS](https://sortablejs.github.io/Sortable/)
- Elemento: `<div id="listaCategorias">` contiene los bloques de categoría.
- Drag & drop habilitado solo para roles 3 y 4.
- Al soltar, se obtiene el nuevo orden de IDs y se envía por `fetch` (AJAX) a la ruta:
  ```js
  fetch('{{ route("admin.menu.category.reorder") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ order: order })
  })
  ```
- El array `order` contiene los IDs en el nuevo orden visual.

---
## 2. Backend
- Ruta: `POST /admin/menu/category/reorder` (definida en `routes/web.php`)
- Controlador: `AdminMenuController@reorderCategories`
- Autorización: Solo roles 3 y 4 (verifica con `Auth::user()->roles_id`)
- Lógica:
  1. Recibe array de IDs (`order`).
  2. Si el orden no cambió, responde éxito sin cambios.
  3. Si cambió, inicia transacción:
     - Desactiva restricciones de FK (`SET FOREIGN_KEY_CHECKS=0`).
     - Asigna IDs temporales altos a cada categoría y actualiza también los `menu_items.category_id`.
     - Luego reasigna IDs correlativos (1,2,3...) y actualiza los `menu_items`.
     - Reactiva FK (`SET FOREIGN_KEY_CHECKS=1`).
  4. Si ocurre error, reactiva FK y responde con error.

---
## 3. Riesgos y Mejoras
| Riesgo | Descripción | Mejora sugerida |
|--------|-------------|-----------------|
| Manipulación de IDs | Cambiar IDs primarios puede romper relaciones externas, logs, auditoría | Usar columna `sort_order` para ordenar sin tocar PK |
| Desactivar FK | Puede dejar datos inconsistentes si falla la transacción | Usar orden lógico, nunca modificar PK |
| Rendimiento | Operación costosa si hay muchas categorías/platos | Optimizar con paginación y orden lógico |

---
## 4. Ejemplo de Código
### JS (drag & drop)
```js
const sortable = new Sortable(categoryList, {
  animation: 150,
  handle: '.drag-handle',
  onEnd: function(evt) {
    const items = categoryList.querySelectorAll('.category-item');
    const order = Array.from(items).map(item => item.getAttribute('data-id'));
    fetch('/admin/menu/category/reorder', { ... });
  }
});
```
### PHP (controlador)
```php
public function reorderCategories(Request $request) {
  if (!in_array(Auth::user()->roles_id, [3, 4])) return response()->json(['success'=>false], 403);
  $order = $request->input('order');
  DB::transaction(function () use ($order) {
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    // ...asignar IDs temporales y luego correlativos...
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
  });
}
```

---
## 5. Archivos Relacionados
- `resources/views/admin/menu.blade.php` (JS y HTML)
- `routes/web.php` (ruta POST)
- `app/Http/Controllers/AdminMenuController.php` (método `reorderCategories`)

---
## 6. Resumen
El reordenamiento actual es funcional pero riesgoso. Se recomienda migrar a un sistema de orden lógico por columna extra.

---
Fin de documento.
