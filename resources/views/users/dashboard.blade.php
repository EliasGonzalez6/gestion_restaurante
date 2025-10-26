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
                <tbody>
                    @foreach($users as $user)
                    <tr>
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
</script>
@endsection
