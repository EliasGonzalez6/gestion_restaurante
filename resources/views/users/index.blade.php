@extends('layouts.app')

@section('content')

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
                                <span class="badge-admin">Administrador</span>
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
                            @if(Auth::user()->roles_id == 4)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('¿Está seguro de que desea eliminar este usuario?')">
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
@endsection
        @endforeach
    </tbody>
</table>
@endsection
