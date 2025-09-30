@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Administrar Menú</h1>
    <div class="row">
        <div class="col-md-4">
            <h4>Categorías</h4>
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
                            <form action="{{ route('admin.menu.category.destroy', $category) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar categoría?')">Borrar</button>
                            </form>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-8">
            <h4>Platos</h4>
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
                                    <form action="{{ route('admin.menu.item.destroy', $item) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar plato?')">Borrar</button>
                                    </form>
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
@endsection
