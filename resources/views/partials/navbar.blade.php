<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        {{-- LOGO: usa una versión blanca del logo en storage --}}
        <a class="navbar-brand" href="{{ route('welcome') }}">
            <img src="{{ asset('storage/photos/Logo.png') }}" alt="Re Chévere" height="40" class="me-2 d-inline-block align-text-top">
            <span class="brand-text">Re Chévere Digital</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item mx-2">
                    @if(request()->routeIs('welcome'))
                        <a class="nav-link" href="#menu">Menú</a>
                    @else
                        <a class="nav-link" href="{{ route('welcome') }}#menu">Menú</a>
                    @endif
                </li>

                @guest
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                    </li>
                @endguest

                @auth
                    @php
                        $photo = Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('storage/photos/fotousuario.png');
                    @endphp

                    <li class="nav-item dropdown mx-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navUserDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ $photo }}" width="36" height="36" class="rounded-circle me-2 avatar-border" style="object-fit:cover;">
                            <span class="nav-username">{{ Auth::user()->name }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">Mi Perfil</a></li>

                            @if(Auth::check() && in_array(Auth::user()->roles_id, [3,4]))
                                <li><a class="dropdown-item" href="{{ route('admin.menu.index') }}">
                                    <i class="bi bi-gear me-2"></i>Administrar
                                </a></li>
                            @endif

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="dropdown-item text-danger fw-semibold" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
