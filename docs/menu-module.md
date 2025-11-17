# Documentación Módulo de Menú

> Proyecto: Gestion Restaurante  
> Fecha: 2025-11-10  
> Autor de compilación: Asistente IA

---
## 1. Objetivo del Módulo
El módulo de **Menú** gestiona las categorías de platos y los ítems (platos) ofrecidos por el restaurante, permitiendo:
- Mostrar el menú público en la página de bienvenida (`welcome`).
- Administración interna: crear, editar, eliminar y reordenar categorías; crear, editar, eliminar y marcar platos como disponibles/agotados.
- Control de visibilidad (estado `is_out`).
- Restricciones según rol del usuario (autorización por `roles_id`).

---
## 2. Componentes Principales
| Tipo | Archivo | Ubicación | Descripción |
|------|---------|-----------|-------------|
| Migración | `2025_09_15_000002_create_categories_table.php` | `database/migrations` | Crea tabla `categories`. |
| Migración | `2025_09_15_000003_create_menu_items_table.php` | `database/migrations` | Crea tabla `menu_items`. |
| Migración | `2025_09_29_000007_add_is_out_to_menu_items_table.php` | `database/migrations` | Agrega columna `is_out` (estado de agotado). |
| Modelo | `Category.php` | `app/Models` | Representa una categoría que agrupa platos. |
| Modelo | `MenuItem.php` | `app/Models` | Representa un plato del menú. |
| Controlador Público | `MenuController.php` | `app/Http/Controllers` | Renderiza el menú público (welcome). |
| Controlador Admin | `AdminMenuController.php` | `app/Http/Controllers` | CRUD de categorías y platos + reordenar + toggle estado. |
| Middleware | `AdminMiddleware.php` | `app/Http/Middleware` | Restringe acceso (roles gerente/supervisor). *Actualmente no aplicado explícitamente en rutas del menú.* |
| Ruta | Definiciones en `web.php` | `routes/web.php` | Rutas públicas y protegidas relacionadas con el menú. |
| Vista pública | `welcome.blade.php` | `resources/views` | Muestra el menú al usuario visitante / autenticado. |
| Vista admin | `admin/menu.blade.php` | `resources/views/admin` | Panel de administración de categorías y platos. |

---
## 3. Esquema de Base de Datos
### 3.1 Tabla `categories`
Campos:
- `id` (PK, auto increment)
- `name` (string, único)
- `description` (string, nullable)
- `timestamps`

Uso: Agrupar platos. Se reordenan indirectamente sobre su `id` (se fuerza reasignación masiva para cambiar orden).

### 3.2 Tabla `menu_items`
Campos:
- `id` (PK)
- `name` (string)
- `description` (text, nullable)
- `price` (decimal 8,2)
- `photo` (string, ruta en `storage/app/public/menu`)
- `category_id` (FK -> `categories.id`, `onDelete('cascade')`)
- `available` (boolean, default true) *[No usado en lógica actual]*
- `is_out` (boolean, default false) indica si el plato está agotado.
- `timestamps`

### 3.3 Relaciones
- `Category` tiene muchos `MenuItem` (`hasMany`).
- `MenuItem` pertenece a `Category` (`belongsTo`).

---
## 4. Modelos
### 4.1 `Category`
Ubicación: `app/Models/Category.php`
```php
class Category extends Model {
  protected $fillable = ['name', 'description'];
  public function menuItems() { return $this->hasMany(MenuItem::class); }
}
```
Notas: expone relación para carga previa (`with('menuItems')`).

### 4.2 `MenuItem`
Ubicación: `app/Models/MenuItem.php`
```php
class MenuItem extends Model {
  protected $fillable = ['category_id','name','description','price','photo','is_out'];
  public function category() { return $this->belongsTo(Category::class); }
}
```
Notas: `is_out` se incluye para toggle de disponibilidad visual.

---
## 5. Rutas Clave
Definidas en `routes/web.php`.

### 5.1 Pública
```php
Route::get('/', [MenuController::class, 'index'])->name('welcome');
```
Entrega menú agrupado por categorías ordenadas por `id`.

### 5.2 Protegidas (middleware `auth`)
Categorías:
```php
Route::post('/admin/menu/category', 'storeCategory')->name('admin.menu.category.store');
Route::get('/admin/menu/category/{category}/edit', 'editCategory')->name('admin.menu.category.edit');
Route::put('/admin/menu/category/{category}', 'updateCategory')->name('admin.menu.category.update');
Route::delete('/admin/menu/category/{category}', 'destroyCategory')->name('admin.menu.category.destroy');
Route::post('/admin/menu/category/reorder', 'reorderCategories')->name('admin.menu.category.reorder');
```
Platos:
```php
Route::post('/admin/menu/item', 'storeItem')->name('admin.menu.item.store');
Route::get('/admin/menu/item/{item}/edit', 'editItem')->name('admin.menu.item.edit');
Route::put('/admin/menu/item/{item}', 'updateItem')->name('admin.menu.item.update');
Route::delete('/admin/menu/item/{item}', 'destroyItem')->name('admin.menu.item.destroy');
Route::post('/admin/menu/item/{item}/toggle', 'toggleItemOut')->name('admin.menu.item.toggle');
```
Panel:
```php
Route::get('/admin/menu', 'index')->name('admin.menu.index');
```

Restricción adicional dentro del controlador (revisión de `roles_id`). No se usa middleware dedicado en las rutas del menú (se podría integrar `AdminMiddleware`).

---
## 6. Controladores
### 6.1 `MenuController`
Función principal: `index()`
- Carga categorías con sus platos: ordenados por `id`.
- Renderiza vista `welcome`.
- No filtra por `is_out` (los platos agotados se muestran con estilos diferenciados).

### 6.2 `AdminMenuController`
Responsabilidades:
1. CRUD Categorías (`storeCategory`, `editCategory`, `updateCategory`, `destroyCategory`).
2. Reordenar categorías (`reorderCategories`):
   - Recibe array de IDs en nuevo orden.
   - Usa transacción + desactiva FK (`SET FOREIGN_KEY_CHECKS=0`).
   - Mueve IDs a rango temporal y luego reasigna correlativos (`1..n`).
   - Actualiza también FK en `menu_items`.
3. CRUD Platos (`storeItem`, `editItem`, `updateItem`, `destroyItem`).
4. Toggle estado (`toggleItemOut`): Invierte booleano `is_out` y redirige según `referer` (welcome o admin).
5. Autorización interna (`authorizeItem($action)`):
   - Crear/editar: roles 3 (Supervisor) y 4 (Gerente).
   - Eliminar: solo rol 4.
   - Toggle disponibilidad: roles 2,3,4 (Incluye quizá Mesero u otro).
6. Vista principal admin (`index`):
   - Restringe acceso si rol == 1 (Cliente) -> `abort(403)`.

Validaciones de entrada detalladas en `storeItem` y `updateItem` (mensajes personalizados).
Carga de imagen se almacena en disco `public` bajo carpeta `menu`.

### 6.3 Flujo Toggle `is_out`
1. Usuario con rol permitido hace POST a ruta `admin.menu.item.toggle`.
2. Controlador invierte estado y guarda.
3. Vista pública `welcome` refleja estilo "Agotado" con badge y opacidad reducida.

---
## 7. Middleware
`AdminMiddleware` verifica que el usuario tenga rol 3 ó 4. Actualmente no se acopla directamente a las rutas del menú; la lógica de rol está embebida en el controlador. Posible mejora: aplicar middleware en grupo de rutas admin para limpiar controlador.

---
## 8. Vistas
### 8.1 `welcome.blade.php`
- Muestra bloques por categoría.
- Cada plato:
  - Foto (si existe) / sin fallback explícito.
  - Badge "Agotado" si `is_out`.
  - Botón toggle (ícono ojo) visible sólo si usuario autenticado con rol > 1.
- Botones de categorías generan scroll suave a sección correspondiente.

### 8.2 `admin/menu.blade.php`
Secciones:
1. Gestión de Categorías:
   - Formulario crear/editar.
   - Lista con drag & drop (SortableJS) para reordenar (solo roles 3 y 4).
   - Botones editar y eliminar (eliminar sólo rol 4).
2. Gestión de Platos:
   - Formulario crear/editar (imagen opcional, validaciones mostradas).
   - Tabla de platos con filtro por categoría, búsqueda por nombre, contador dinámico.
   - Switch de estado `is_out` (toggle disponibilidad) con formulario POST.
   - Acciones editar y eliminar (eliminar sólo rol 4).
3. Modal de confirmación para eliminaciones (categoría/plato).
4. Scripts: búsqueda, filtrado, drag & drop, eliminación.

Estados visuales:
- Disponible: badge verde personalizado.
- Agotado: badge rojo `Agotado` + switch desmarcado (lógica invertida: checked = disponible).

---
## 9. Autorización y Roles
Roles asumidos según IDs (inferido por uso):
- `1`: Cliente (sin acceso admin).
- `2`: Rol intermedio (puede toggle disponibilidad).
- `3`: Supervisor (CRUD excepto delete de ítems y categorías). Puede reordenar.
- `4`: Gerente (permiso total).

Autorización no centralizada: se dispersa en métodos `authorizeItem` y condicionales en vista (`Auth::user()->roles_id`). Mejora propuesta: políticas (`php artisan make:policy`) y middleware específicos.

---
## 10. Flujo General de Datos
1. Usuario visitante llega a `/` -> `MenuController@index` -> carga categorías + sus `menuItems`.
2. Usuario admin accede a `/admin/menu` (autenticado y rol > 1) -> `AdminMenuController@index` -> vista administración.
3. Crear categoría -> POST -> validación -> `Category::create()` -> redirección.
4. Reordenar categorías -> JS drag & drop genera array -> POST JSON -> reasignación IDs.
5. Crear plato -> POST con imagen -> almacena archivo -> `MenuItem::create()`.
6. Toggle estado -> POST -> actualiza `is_out` -> redirección según origen.
7. Vista pública refleja `is_out` con estilos.

---
## 11. Consideraciones Técnicas y Riesgos
| Aspecto | Riesgo / Observación | Mejora Sugerida |
|---------|----------------------|------------------|
| Reordenar categorías | Cambia IDs primarios (operación agresiva) | Agregar columna `sort_order` y ordenar por ella. |
| Autorización | Lógica manual en controlador y vista | Usar Policies + Middleware por rol. |
| Campo `available` | No se usa actualmente | Eliminar o implementar lógica de disponibilidad real. |
| Toggle de estado | Formularios múltiples (posible carga) | Usar AJAX para evitar reload completo. |
| Imágenes | No hay limpieza de archivos al eliminar plato | Agregar eliminación física en `destroyItem`. |
| Validaciones | Repetidas en store/update | Extraer a Form Request (`php artisan make:request`). |
| Rendimiento | Carga completa de todas categorías y platos cada vez | Cacheo o paginación si crece el volumen. |
| Seguridad | Reordenar desactiva FK y manipula IDs | Sustituir por modelo de orden no destructivo. |

---
## 12. Ejemplos de Código Clave
### Toggle Estado
```php
public function toggleItemOut(MenuItem $item) {
  $this->authorizeItem('toggle', $item);
  $item->is_out = !$item->is_out;
  $item->save();
  // Redirecciones según referer
}
```

### Reordenar Categorías (actual)
```php
DB::transaction(function () use ($order) {
  DB::statement('SET FOREIGN_KEY_CHECKS=0');
  $tempIdBase = 900000;
  foreach ($order as $i => $id) {
    DB::statement('UPDATE categories SET id=? WHERE id=?', [$tempIdBase+$i, $id]);
    DB::statement('UPDATE menu_items SET category_id=? WHERE category_id=?', [$tempIdBase+$i, $id]);
  }
  foreach ($order as $i => $id) {
    $temp = $tempIdBase+$i; $final = $i+1;
    DB::statement('UPDATE categories SET id=? WHERE id=?', [$final, $temp]);
    DB::statement('UPDATE menu_items SET category_id=? WHERE category_id=?', [$final, $temp]);
  }
  DB::statement('SET FOREIGN_KEY_CHECKS=1');
});
```

---
## 13. Posibles Mejoras Futuras
- Reemplazar reordenamiento por columna `position`.
- Implementar Policies (`CategoryPolicy`, `MenuItemPolicy`).
- Añadir pruebas feature (CRUD + toggle + reordenar).
- Integrar Livewire o Vue para administración más fluida.
- Usar soft deletes si se requiere historial.
- Cachear menú público (`remember` en consulta) para rendimiento.

---
## 14. Resumen para Exposición Oral
1. "El módulo de Menú permite administrar categorías y platos del restaurante".
2. "Desde el panel admin se crean categorías y platos, se suben imágenes y se marca si un plato está agotado".
3. "Los roles definen qué acciones se permiten: el gerente controla todo, supervisor gestiona y reordena, otros roles pueden marcar agotado".
4. "La vista pública muestra el menú completo agrupado por categoría; los platos agotados aparecen atenuados y con badge".
5. "El reordenamiento actual manipula IDs directamente (explicar riesgo y mejora propuesta)".
6. "Propuesta de mejoras: usar `position`, policies, tests y optimizaciones de rendimiento".

---
## 15. Diagrama Conceptual (Texto)
[Usuario] -> (Rutas) -> Controladores -> (Modelos/Eloquent) -> BD  
[Admin] -> Panel -> CRUD + Toggle + Reordenar -> Persistencia -> Vista actualizada  
[Visitante] -> Página Welcome -> Menú cacheado (potencial) -> Render Blade

---
## 16. Checklist Rápido
- Migraciones correctas: Sí.
- Relaciones definidas: Sí.
- CRUD funcional: Sí.
- Estado visual de agotado: Sí (`is_out`).
- Autorización básica por rol: Sí (manual).
- Gestión de imágenes: Parcial (sin cleanup en delete).
- Reordenamiento: Sí (agresivo sobre IDs).

---
## 17. Conclusión
El módulo cumple con las funciones esenciales de catálogo de platos y administración interna. Presenta oportunidades claras de refactor para mejorar mantenibilidad, seguridad y escalabilidad.

---
Fin de documento.
