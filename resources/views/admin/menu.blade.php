@extends('admin.dashboard')

@section('admin_content')
<link rel="stylesheet" href="{{ asset('css/delete-modal.css') }}">

<div class="container">
    <h1>Administrar Menú</h1>
    <div class="row">
        <div class="col-md-4">
            <h4>Categorías</h4>
            @if(isset($category))
            <form action="{{ route('admin.menu.category.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-2">
                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" placeholder="Nombre de la categoría" required>
                </div>
                <div class="mb-2">
                    <input type="text" name="description" class="form-control" value="{{ $category->description }}" placeholder="Descripción (opcional)">
                </div>
                <button class="btn btn-primary btn-sm">Actualizar</button>
                <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
            </form>
            @else
            <form action="{{ route('admin.menu.category.store') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <input type="text" name="name" class="form-control" placeholder="Nombre de la categoría" required>
                </div>
                <div class="mb-2">
                    <input type="text" name="description" class="form-control" placeholder="Descripción (opcional)">
                </div>
                <button class="btn btn-success btn-sm">Agregar</button>
            </form>
            @endif
            <ul class="list-group mt-3">
                @foreach($categories as $category)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <strong>{{ $category->name }}</strong>
                            @if($category->description)
                                <br><small class="text-muted">{{ $category->description }}</small>
                            @endif
                        </span>
                        <span>
                            <a href="{{ route('admin.menu.category.edit', $category) }}" class="btn btn-warning btn-sm">Editar</a>
                            @if(Auth::user()->roles_id == 4)
                            <form id="delete-category-form-{{ $category->id }}" action="{{ route('admin.menu.category.destroy', $category) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" onclick="showDeleteModal('category', '{{ $category->id }}', '{{ $category->name }}')">Borrar</button>
                            </form>
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-8">
            <h4>Platos</h4>
            @if(isset($editItem))
            <form action="{{ route('admin.menu.item.update', $editItem) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <select name="category_id" class="form-select" required>
                            <option value="">Categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $editItem->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="text" name="name" class="form-control" value="{{ $editItem->name }}" placeholder="Nombre del plato" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="number" name="price" class="form-control" value="{{ $editItem->price }}" placeholder="Precio" step="0.01" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 mb-2">
                        <input type="text" name="description" class="form-control" value="{{ $editItem->description }}" placeholder="Descripción (opcional)">
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="file" name="photo" class="form-control">
                    </div>
                </div>
                <button class="btn btn-primary btn-sm">Actualizar</button>
                <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
            </form>
            @else
            <form action="{{ route('admin.menu.item.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <select name="category_id" class="form-select" required>
                            <option value="">Categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="text" name="name" class="form-control" placeholder="Nombre del plato" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="number" name="price" class="form-control" placeholder="Precio" step="0.01" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 mb-2">
                        <input type="text" name="description" class="form-control" placeholder="Descripción (opcional)">
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="file" name="photo" class="form-control">
                    </div>
                </div>
                <button class="btn btn-success btn-sm">Agregar</button>
            </form>
            @endif
            <div class="mt-4">
                @foreach($categories as $category)
                    <h5>{{ $category->name }}</h5>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Foto</th>
                                <th>Agotado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($category->menuItems as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>
                                    @if($item->photo)
                                        <img src="{{ asset('storage/'.$item->photo) }}" width="50" style="object-fit:cover;max-height:50px;">
                                    @endif
                                </td>
                                <td>
                                    @if($item->is_out)
                                        <span class="badge bg-danger">Agotado</span>
                                    @else
                                        <span class="badge bg-success">Disponible</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.menu.item.toggle', $item) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button class="btn btn-secondary btn-sm">@if($item->is_out) Marcar disponible @else Marcar agotado @endif</button>
                                    </form>
                                    <a href="{{ route('admin.menu.item.edit', $item) }}" class="btn btn-warning btn-sm">Editar</a>
                                    @if(Auth::user()->roles_id == 4)
                                    <form id="delete-item-form-{{ $item->id }}" action="{{ route('admin.menu.item.destroy', $item) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" onclick="showDeleteModal('item', '{{ $item->id }}', '{{ $item->name }}')">Borrar</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
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
</script>
@endsection
