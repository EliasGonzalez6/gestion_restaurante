<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
    @include('partials.navbar')

    <div class="container mt-5">
        <section id="menu">
            <h2 class="mb-4">Menú del Restaurante</h2>
            @foreach($categories as $category)
                <div class="mb-5">
                    <h4>{{ $category->name }}</h4>
                    @if($category->description)
                        <p class="text-muted">{{ $category->description }}</p>
                    @endif
                    <div class="row">
                        @forelse($category->menuItems as $item)
                            <div class="col-12 mb-4">
                                <div class="card flex-row align-items-center menu-item-card position-relative p-2" style="min-height: 140px;">
                                    @if($item->photo)
                                        <div class="menu-img-container me-3" style="width: 140px; min-width: 140px; height: 110px; overflow: hidden; border-radius: 10px;">
                                            <img src="{{ asset('storage/'.$item->photo) }}" class="h-100 w-100" style="object-fit:cover;">
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <h5 class="card-title mb-0">{{ $item->name }}</h5>
                                            @auth
                                                @if(Auth::user()->roles_id > 1)
                                                    <button class="btn btn-outline-secondary btn-sm p-0 ms-2 toggle-agotado" title="Marcar como no disponible" style="vertical-align:middle; width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:50%;">
                                                        <i class="bi bi-eye-slash" style="font-size:1.3rem;"></i>
                                                    </button>
                                                @endif
                                            @endauth
                                        </div>
                                        <p class="card-text mb-1">{{ $item->description }}</p>
                                        <p class="card-text fw-bold mb-0">${{ number_format($item->price, 2) }}</p>
                                    </div>
                                    <div class="agotado-overlay position-absolute top-50 start-50 translate-middle w-100 text-center d-none" style="z-index:2;">
                                        <span class="badge bg-danger fs-5">No disponible</span>
                                    </div>
                                </div>
                            </div>
</section>
<style>
.menu-item-card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    transition: opacity 0.3s, box-shadow 0.3s;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
}
.menu-item-card.agotado {
    opacity: 0.5;
    box-shadow: none;
}
.agotado-overlay {
    pointer-events: none;
}
.menu-img-container img {
    border-radius: 10px;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-agotado').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const card = btn.closest('.menu-item-card');
            const overlay = card.querySelector('.agotado-overlay');
            card.classList.toggle('agotado');
            overlay.classList.toggle('d-none');
        });
    });
});
</script>
                        @empty
                            <div class="col-12"><em>No hay platos en esta categoría.</em></div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </section>
    </div>
