@extends('admin.dashboard')

@section('admin_content')
<link rel="stylesheet" href="{{ asset('css/admin-users.css') }}">
<link rel="stylesheet" href="{{ asset('css/delete-modal.css') }}">

<div class="main-content">
    <!-- Header de Página -->
    <div class="page-header">
        <h1><i class="fas fa-users"></i> Gestión de Usuarios</h1>
        <p>Administra los usuarios del sistema</p>
    </div>

    <!-- Card de Tabla -->
    <div class="admin-card">
        <div class="card-header-custom">
            <h3>Lista de Usuarios</h3>
            <a href="{{ route('users.create') }}" class="btn-add-user">
                <i class="fas fa-plus"></i> Agregar Usuario
            </a>
        </div>
        
        <!-- Filtros y Buscador -->
        <div class="filters-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar por nombre, email o DNI..." class="search-input">
            </div>
            
            <div class="filter-buttons">
                <button class="filter-btn active" data-role="all">
                    <i class="fas fa-users"></i> Todos
                </button>
                <button class="filter-btn" data-role="4">
                    <i class="fas fa-user-tie"></i> Gerentes
                </button>
                <button class="filter-btn" data-role="3">
                    <i class="fas fa-user-shield"></i> Supervisores
                </button>
                <button class="filter-btn" data-role="2">
                    <i class="fas fa-concierge-bell"></i> Mozos
                </button>
                <button class="filter-btn" data-role="1">
                    <i class="fas fa-user"></i> Clientes
                </button>
            </div>
            
            <div class="results-counter">
                Mostrando <span id="visibleCount">0</span> de <span id="totalCount">0</span> usuarios
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>DNI</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    @foreach($users as $user)
                    <tr data-role="{{ $user->roles_id }}" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-dni="{{ strtolower($user->dni ?? '') }}">
                        <td><strong>{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>
                            @php
                                $photo = $user->photo ? asset('storage/'.$user->photo) : asset('storage/photos/fotousuario.png');
                            @endphp
                            <img src="{{ $photo }}" class="user-photo" alt="{{ $user->name }}">
                        </td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->dni ?? 'N/A' }}</td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td>{{ $user->address ?? 'N/A' }}</td>
                        <td>
                            @if($user->roles_id == 4)
                                <span class="badge-admin">Gerente</span>
                            @elseif($user->roles_id == 3)
                                <span class="badge-supervisor">Supervisor</span>
                            @else
                                <span class="badge-user">{{ $user->rol->name }}</span>
                            @endif
                        </td>
                        <td>
                            @php $isSupervisor = Auth::user()->roles_id == 3; @endphp
                            @if(!($user->roles_id == 4 && $isSupervisor))
                                <a href="{{ route('users.edit', $user) }}" class="btn-edit">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            @endif
                            @if(Auth::user()->roles_id == 4 && $user->roles_id != 4)
                                <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-delete" onclick="showDeleteModal('{{ $user->id }}', '{{ $user->name }}')">
                                        <i class="fas fa-trash"></i> Borrar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Contador de resultados -->
        <div class="results-counter">
            Mostrando <span id="visibleCount">{{ count($users) }}</span> de <span id="totalCount">{{ count($users) }}</span> usuarios
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
            <p class="delete-modal-message">¿Está seguro de que desea eliminar este usuario?</p>
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

function showDeleteModal(userId, userName) {
    currentDeleteFormId = 'delete-form-' + userId;
    document.getElementById('deleteItemName').textContent = userName;
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

// ========== FILTRADO Y BÚSQUEDA DE USUARIOS ==========
const searchInput = document.getElementById('searchInput');
const filterButtons = document.querySelectorAll('.filter-btn');
const tableRows = document.querySelectorAll('#usersTableBody tr');
const totalCount = tableRows.length;
let currentFilter = 'all';

// Actualizar contador de resultados
function updateCounter() {
    const visibleRows = document.querySelectorAll('#usersTableBody tr:not([style*="display: none"])');
    document.getElementById('visibleCount').textContent = visibleRows.length;
    document.getElementById('totalCount').textContent = totalCount;
}

// Función de filtrado
function filterUsers() {
    const searchTerm = searchInput.value.toLowerCase().trim();
    
    tableRows.forEach(row => {
        const role = row.getAttribute('data-role');
        const name = row.getAttribute('data-name');
        const email = row.getAttribute('data-email');
        const dni = row.getAttribute('data-dni');
        
        // Verificar filtro de rol
        const roleMatch = currentFilter === 'all' || role === currentFilter;
        
        // Verificar búsqueda (nombre, email o DNI)
        const searchMatch = searchTerm === '' || 
                           name.includes(searchTerm) || 
                           email.includes(searchTerm) || 
                           dni.includes(searchTerm);
        
        // Mostrar u ocultar fila
        if (roleMatch && searchMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    updateCounter();
}

// Event listener para búsqueda
searchInput.addEventListener('input', filterUsers);

// Event listeners para botones de filtro
filterButtons.forEach(button => {
    button.addEventListener('click', function() {
        // Remover clase active de todos los botones
        filterButtons.forEach(btn => btn.classList.remove('active'));
        
        // Agregar clase active al botón clickeado
        this.classList.add('active');
        
        // Actualizar filtro actual
        currentFilter = this.getAttribute('data-role');
        
        // Filtrar usuarios
        filterUsers();
    });
});

// Inicializar contador
updateCounter();
</script>
@endsection
