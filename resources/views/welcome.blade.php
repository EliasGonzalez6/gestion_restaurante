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
        <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
</head>
<body>

    {{-- NAVBAR --}}
    @include('partials.navbar')

    {{-- CARRUSEL --}}
    <div id="heroCarousel" class="carousel slide hero-section mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1600');">
                <div class="hero-content text-center d-flex flex-column justify-content-center align-items-center h-100">
                    <h1 class="display-5 fw-bold text-light mb-3">Sabores que te hacen sentir en casa 🇻🇪✨</h1>
                    <a href="#menu" class="btn btn-warning px-4 py-2 fw-semibold">Explora nuestro menú</a>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1600');">
                <div class="hero-content text-center d-flex flex-column justify-content-center align-items-center h-100">
                    <h1 class="display-6 fw-bold text-light mb-3">Tradición y Sabor en Cada Bocado</h1>
                    <a href="#menu" class="btn btn-warning px-4 py-2 fw-semibold">Ver especialidades</a>
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN MENÚ --}}
    <section class="menu-section py-5" id="menu">
        <div class="container">
            <div class="section-title mb-4">
                <h2 class="text-center mb-2">Nuestro Menú</h2>
                <p class="text-muted fs-5 text-center">Selecciona tu categoría favorita</p>
                <!-- Botones de categorías -->
                <div class="row mb-4 gx-2 gy-2 category-buttons-bar justify-content-center">
                    @foreach($categories as $category)
                        <div class="col-4 col-md-3 col-lg-2">
                            <button class="category-btn w-100" data-category-id="cat-{{ $category->id }}">
                                {{ $category->name }}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            @foreach($categories as $category)
                <div class="category-block mb-5">
                    <h3 id="cat-{{ $category->id }}" class="text-center text-uppercase mt-5 mb-2">{{ $category->name }}</h3>
                    <div class="category-underline mx-auto mb-2"></div>
                    @if(!empty($category->description))
                        <p class="text-center text-muted fs-5 mb-2">{{ $category->description }}</p>
                    @endif
                    <div class="row g-4">
                    @forelse($category->menuItems as $item)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="dish-card h-100 {{ $item->is_out ? 'opacity-50' : '' }}">
                                <div class="dish-img">
                                    @if($item->photo)
                                        <img src="{{ asset('storage/'.$item->photo) }}" alt="{{ $item->name }}" class="img-fluid rounded w-100">
                                    @endif
                                    @if($item->is_out)
                                        <span class="badge bg-danger position-absolute top-0 end-0 m-3">Agotado</span>
                                    @endif
                                </div>
                                <div class="dish-info">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h3 class="dish-name mb-0 fs-5">{{ $item->name }}</h3>
                                        @auth
                                            @if(Auth::user()->roles_id > 1)
                                                <form action="{{ route('admin.menu.item.toggle', $item) }}" method="POST" class="ms-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm rounded-circle dish-eye-btn" title="{{ $item->is_out ? 'Marcar como disponible' : 'Marcar como agotado' }}">
                                                        <i class="fa{{ $item->is_out ? 's' : 'r' }} fa-eye{{ $item->is_out ? '-slash' : '' }}"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                    <p class="dish-description small">{{ $item->description }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="dish-price fw-bold">${{ number_format($item->price, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center"><em>No hay platos en esta categoría.</em></div>
                    @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Navbar scroll efecto
        const nav = document.querySelector('.navbar-custom');
        const onScroll = () => {
            if (window.scrollY > 20) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');
        };
        window.addEventListener('scroll', onScroll);
        onScroll();

        // Scroll suave a categoría
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const catId = this.getAttribute('data-category-id');
                const target = document.getElementById(catId);
                if (target) {
                    const yOffset = -80; // Ajusta si tienes navbar fija
                    const y = target.getBoundingClientRect().top + window.pageYOffset + yOffset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            });
        });
    });
    </script>

    @include('partials.footer')
</body>
</html>
