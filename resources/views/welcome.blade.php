<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante Re Chévere Digital</title>

    <!-- Bootstrap + Iconos -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
  <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
</head>
<body>

    {{-- NAVBAR --}}
    @include('partials.navbar')

    {{-- CARRUSEL --}}
    <div id="heroCarousel" class="carousel slide hero-section" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1600');">
                <div class="hero-content">
                    <h1>Sabores que te hacen sentir en casa 🇻🇪✨</h1>
                    <a href="#menu" class="hero-btn">Explora nuestro menú</a>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1600');">
                <div class="hero-content">
                    <h1>Tradición y Sabor en Cada Bocado</h1>
                    <a href="#menu" class="hero-btn">Ver especialidades</a>
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN MENÚ --}}
    <section class="menu-section" id="menu">
        <div class="container">
            <div class="section-title">
                <h2>Nuestro Menú</h2>
                <p class="text-muted fs-5">Selecciona tu categoría favorita</p>
            </div>

            @foreach($categories as $category)
                <h3 class="text-center text-uppercase mt-5 mb-4">{{ $category->name }}</h3>
                <div class="row">
                    @forelse($category->menuItems as $item)
                        <div class="col-lg-4 col-md-6">
                            <div class="dish-card {{ $item->is_out ? 'opacity-50' : '' }}">
                                <div class="dish-img">
                                    @if($item->photo)
                                        <img src="{{ asset('storage/'.$item->photo) }}" alt="{{ $item->name }}">
                                    @endif
                                    @if($item->is_out)
                                        <span class="badge bg-danger position-absolute top-0 end-0 m-3">No disponible</span>
                                    @endif
                                </div>
                                <div class="dish-info">
                                    <h3 class="dish-name">{{ $item->name }}</h3>
                                    <p class="dish-description">{{ $item->description }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="dish-price">${{ number_format($item->price, 2) }}</span>
                                        @auth
                                            @if(Auth::user()->roles_id > 1)
                                                <form action="{{ route('admin.menu.item.toggle', $item) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm rounded-circle" title="{{ $item->is_out ? 'Marcar como disponible' : 'Marcar como no disponible' }}">
                                                        <i class="bi bi-eye{{ $item->is_out ? '' : '-slash' }}"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center"><em>No hay platos en esta categoría.</em></div>
                    @endforelse
                </div>
            @endforeach
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const nav = document.querySelector('.navbar-custom');
        const onScroll = () => {
            if (window.scrollY > 20) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');
        };
        window.addEventListener('scroll', onScroll);
        onScroll();
    });
</script>

</body>
</html>
