@extends('admin.dashboard')

@section('admin_content')
<link rel="stylesheet" href="{{ asset('css/admin-menu.css') }}">
<link rel="stylesheet" href="{{ asset('css/delete-modal.css') }}">
<!-- SortableJS para drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<div class="main-content">
    <!-- HEADER DE PÁGINA -->
    <div class="page-header">
        <h1><i class="fas fa-utensils"></i> Gestión de Menú</h1>
        <p>Administra categorías y platos del restaurante</p>
    </div>

    <!-- SECCIÓN CATEGORÍAS -->
    <div class="admin-card">
        <h3 class="card-title">
            <i class="fas fa-tags"></i> Categorías
        </h3>
        
        <!-- Formulario para agregar/editar categoría -->
        <form class="category-form" id="formAgregarCategoria" 
              action="@if(isset($category)){{ route('admin.menu.category.update', $category) }}@else{{ route('admin.menu.category.store') }}@endif" 
              method="POST">
            @csrf
            @if(isset($category))
                @method('PUT')
            @endif
            <div class="row">
                <div class="col-md-5 mb-3">
                    <label class="form-label">Nombre de la Categoría *</label>
                    <input 
                        type="text" 
                        name="name"
                        class="form-control" 
                        id="nombreCategoria"
                        value="@if(isset($category)){{ $category->name }}@endif"
                        placeholder="Ej: Entradas, Platos Principales"
                        required>
                </div>
                <div class="col-md-5 mb-3">
                    <label class="form-label">Descripción (Opcional)</label>
                    <input 
                        type="text" 
                        name="description"
                        class="form-control" 
                        id="descripcionCategoria"
                        value="@if(isset($category)){{ $category->description }}@endif"
                        placeholder="Breve descripción de la categoría">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn-primary-custom w-100" id="btnSubmitCategoria">
                        @if(isset($category))
                            <i class="fas fa-save"></i> Actualizar
                        @else
                            <i class="fas fa-plus"></i> Agregar
                        @endif
                    </button>
                </div>
            </div>
            @if(isset($category))
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
            @endif
        </form>

        <!-- Separador visual -->
        <div class="section-separator"></div>

        <!-- Subtítulo de lista -->
        <h5 class="list-subtitle">
            <i class="fas fa-list"></i> Categorías Existentes
        </h5>

        <!-- Lista de categorías -->
        <div class="category-list" id="listaCategorias">
            @foreach($categories as $cat)
            <div class="category-item" data-id="{{ $cat->id }}">
                @if(in_array(Auth::user()->roles_id, [3, 4]))
                <div class="drag-handle" title="Arrastrar para reordenar">
                    <i class="fas fa-grip-vertical"></i>
                </div>
                @endif
                <div class="category-content">
                    <div class="category-name">{{ $cat->name }}</div>
                    <div class="category-desc">{{ $cat->description ?? 'Sin descripción' }}</div>
                </div>
                <div class="category-actions">
                    <a href="{{ route('admin.menu.category.edit', $cat) }}" class="btn-edit-small" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    @if(Auth::user()->roles_id == 4)
                    <form id="delete-category-form-{{ $cat->id }}" action="{{ route('admin.menu.category.destroy', $cat) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="button" class="btn-delete-small" title="Borrar" onclick="showDeleteModal('category', '{{ $cat->id }}', '{{ $cat->name }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- SECCIÓN PLATOS -->
    <div class="admin-card">
        <h3 class="card-title">
            <i class="fas fa-hamburger"></i> Platos
        </h3>
        
        <!-- Formulario para agregar/editar plato -->
        <form class="dish-form" id="formAgregarPlato" 
              action="@if(isset($editItem)){{ route('admin.menu.item.update', $editItem) }}@else{{ route('admin.menu.item.store') }}@endif" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf
            @if(isset($editItem))
                @method('PUT')
            @endif

            <!-- Mostrar errores generales -->
            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <strong><i class="fas fa-exclamation-triangle"></i> Error al guardar el plato:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Categoría *</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" id="categoriaPlato" required>
                        <option value="" selected>Seleccionar categoría</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @if(isset($editItem) && $editItem->category_id == $cat->id) selected @endif>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Nombre del Plato *</label>
                    <input 
                        type="text" 
                        name="name"
                        class="form-control @error('name') is-invalid @enderror" 
                        id="nombrePlato"
                        value="@if(isset($editItem)){{ $editItem->name }}@else{{ old('name') }}@endif"
                        placeholder="Ej: Arepa Reina Pepiada"
                        required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Descripción (Opcional)</label>
                    <input 
                        type="text" 
                        name="description"
                        class="form-control @error('description') is-invalid @enderror" 
                        id="descripcionPlato"
                        value="@if(isset($editItem)){{ $editItem->description }}@else{{ old('description') }}@endif"
                        placeholder="Descripción breve">
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Precio *</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input 
                            type="number" 
                            name="price"
                            class="form-control @error('price') is-invalid @enderror" 
                            id="precioPlato"
                            value="@if(isset($editItem)){{ $editItem->price }}@else{{ old('price') }}@endif"
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Foto del Plato (Opcional)</label>
                    <input 
                        type="file" 
                        name="photo"
                        class="form-control @error('photo') is-invalid @enderror" 
                        id="fotoPlato"
                        accept="image/jpeg,image/jpg,image/png">
                    @if(isset($editItem) && $editItem->photo)
                        <small class="text-muted d-block mt-1">Foto actual: {{ basename($editItem->photo) }}</small>
                    @endif
                    <small class="text-muted d-block mt-1">Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo: 10MB</small>
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn-primary-custom w-100" id="btnSubmitPlato">
                        @if(isset($editItem))
                            <i class="fas fa-save"></i> Actualizar Plato
                        @else
                            <i class="fas fa-plus"></i> Agregar Plato
                        @endif
                    </button>
                </div>
            </div>
            @if(isset($editItem))
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
            @endif
        </form>

        <!-- Separador visual -->
        <div class="section-separator"></div>

        <!-- Subtítulo de lista -->
        <h5 class="list-subtitle">
            <i class="fas fa-list"></i> Platos del Menú
        </h5>

        <!-- FILTROS Y BÚSQUEDA -->
        <div class="filters-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input 
                    type="text" 
                    id="searchDishInput" 
                    class="search-input" 
                    placeholder="Buscar plato por nombre...">
            </div>
            
            <div class="filter-buttons">
                <button class="filter-btn active" data-category="all">
                    <i class="fas fa-list"></i> Todos
                </button>
                @foreach($categories as $cat)
                <button class="filter-btn" data-category="{{ $cat->id }}">
                    <i class="fas fa-tag"></i> {{ $cat->name }}
                </button>
                @endforeach
            </div>
            
            <div class="results-counter">
                Mostrando <span id="visibleDishCount">0</span> de <span id="totalDishCount">0</span> platos
            </div>
        </div>

        <!-- Tabla de platos -->
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaPlatosBody">
                    @foreach($categories as $cat)
                        @foreach($cat->menuItems as $item)
                        <tr data-category="{{ $item->category_id }}" data-name="{{ strtolower($item->name) }}">
                            <td>
                                @if($item->photo)
                                    <img src="{{ asset('storage/'.$item->photo) }}" 
                                         alt="{{ $item->name }}" 
                                         class="dish-photo">
                                @else
                                    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200" 
                                         alt="{{ $item->name }}" 
                                         class="dish-photo">
                                @endif
                            </td>
                            <td>
                                <strong class="dish-name-table">{{ $item->name }}</strong>
                            </td>
                            <td>
                                <span class="dish-desc-table">{{ $item->description ?? 'Sin descripción' }}</span>
                            </td>
                            <td>
                                <strong class="dish-price-table">${{ number_format($item->price, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <div class="status-container">
                                    <span class="@if($item->is_out)badge-soldout @else badge-available @endif">
                                        @if($item->is_out)Agotado @else Disponible @endif
                                    </span>
                                    <form action="{{ route('admin.menu.item.toggle', $item) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" @if(!$item->is_out) checked @endif onchange="this.form.submit()">
                                        </div>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.menu.item.edit', $item) }}" class="btn-edit-table">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    @if(Auth::user()->roles_id == 4)
                                    <form id="delete-item-form-{{ $item->id }}" action="{{ route('admin.menu.item.destroy', $item) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-delete-table" onclick="showDeleteModal('item', '{{ $item->id }}', '{{ $item->name }}')">
                                            <i class="fas fa-trash"></i> Borrar
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal de Confirmación de Eliminación -->
<div class="delete-modal-overlay" id="deleteModal">
    <div class="delete-modal">
        <div class="delete-modal-header">
            <div class="delete-modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2 class="delete-modal-title">Confirmar Eliminación</h2>
        </div>
        <div class="delete-modal-body">
            <p class="delete-modal-message" id="deleteMessage">¿Está seguro de que desea eliminar este elemento?</p>
            <p class="delete-modal-item" id="deleteItemName"></p>
            <p class="delete-modal-warning">
                <i class="fas fa-info-circle"></i> Esta acción no se puede deshacer
            </p>
        </div>
        <div class="delete-modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="hideDeleteModal()">
                <i class="fas fa-times"></i> Cancelar
            </button>
            <button type="button" class="modal-btn modal-btn-delete" onclick="confirmDelete()">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
    </div>
</div>

<script>
let currentDeleteFormId = null;

function showDeleteModal(type, id, name) {
    const formId = type === 'category' 
        ? 'delete-category-form-' + id 
        : 'delete-item-form-' + id;
    
    const message = type === 'category' 
        ? '¿Está seguro de que desea eliminar esta categoría?' 
        : '¿Está seguro de que desea eliminar este plato?';
    
    currentDeleteFormId = formId;
    document.getElementById('deleteMessage').textContent = message;
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteModal').classList.add('show');
}

function hideDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
    currentDeleteFormId = null;
}

function confirmDelete() {
    if (currentDeleteFormId) {
        document.getElementById(currentDeleteFormId).submit();
    }
}

// Cerrar modal al hacer clic fuera de él
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});

// ========== FILTRADO Y BÚSQUEDA DE PLATOS ==========
const searchDishInput = document.getElementById('searchDishInput');
const filterDishButtons = document.querySelectorAll('.filter-btn');
const dishRows = document.querySelectorAll('#tablaPlatosBody tr');
const totalDishCount = dishRows.length;
let currentDishFilter = 'all';

// Actualizar contador de resultados
function updateDishCounter() {
    const visibleRows = document.querySelectorAll('#tablaPlatosBody tr:not([style*="display: none"])');
    document.getElementById('visibleDishCount').textContent = visibleRows.length;
    document.getElementById('totalDishCount').textContent = totalDishCount;
}

// Función de filtrado de platos
function filterDishes() {
    const searchTerm = searchDishInput.value.toLowerCase().trim();
    
    dishRows.forEach(row => {
        const category = row.getAttribute('data-category');
        const name = row.getAttribute('data-name');
        
        // Verificar filtro de categoría
        const categoryMatch = currentDishFilter === 'all' || category === currentDishFilter;
        
        // Verificar búsqueda por nombre
        const searchMatch = searchTerm === '' || name.includes(searchTerm);
        
        // Mostrar u ocultar fila
        if (categoryMatch && searchMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    updateDishCounter();
}

// Event listener para búsqueda
searchDishInput.addEventListener('input', filterDishes);

// Event listeners para botones de filtro
filterDishButtons.forEach(button => {
    button.addEventListener('click', function() {
        // Remover clase active de todos los botones
        filterDishButtons.forEach(btn => btn.classList.remove('active'));
        
        // Agregar clase active al botón clickeado
        this.classList.add('active');
        
        // Actualizar filtro actual
        currentDishFilter = this.getAttribute('data-category');
        
        // Filtrar platos
        filterDishes();
    });
});

// Inicializar contador
updateDishCounter();

// ========== DRAG AND DROP PARA CATEGORÍAS ==========
@if(in_array(Auth::user()->roles_id, [3, 4]))
const categoryList = document.getElementById('listaCategorias');

if (categoryList) {
    const sortable = new Sortable(categoryList, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: function(evt) {
            // Obtener el nuevo orden de IDs
            const items = categoryList.querySelectorAll('.category-item');
            const order = Array.from(items).map(item => item.getAttribute('data-id'));
            
            // Enviar el nuevo orden al servidor
            fetch('{{ route("admin.menu.category.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order: order })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    console.log('Orden actualizado correctamente');
                    // Recargar la página para ver los cambios
                    location.reload();
                } else {
                    console.error('Error al actualizar el orden:', data.message);
                    alert('Error al actualizar el orden: ' + (data.message || 'Error desconocido'));
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error completo:', error);
                alert('Error de conexión: ' + error.message);
                location.reload();
            });
        }
    });
}
@endif
</script>
@endsection
