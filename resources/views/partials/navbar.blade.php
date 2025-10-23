<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container-fluid px-3">
        <div class="row w-100 align-items-center flex-nowrap gx-0">
            <div class="col-9 col-sm-10 col-md-6">
                {{-- LOGO y nombre: en móvil/tablet en columna, en escritorio en fila --}}
                <a class="navbar-brand mb-0 w-100 d-flex flex-column flex-md-row align-items-center" href="{{ route('welcome') }}">
                    <img src="{{ asset('storage/photos/Logo.png') }}" alt="Re Chévere" height="40" class="me-0 me-md-2 d-none d-lg-inline-block align-text-top logo-navbar">
                    <span class="brand-text d-block text-break text-center text-md-start mt-0 mt-md-0">Re Chévere Digital</span>
                </a>
            </div>
            <div class="col-3 col-sm-2 col-md-6 d-flex justify-content-end align-items-center">
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <svg width="28" height="28" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" class="hamburger-icon">
                        <rect y="6" width="30" height="3" rx="1.5" fill="#fff"/>
                        <rect y="13.5" width="30" height="3" rx="1.5" fill="#fff"/>
                        <rect y="21" width="30" height="3" rx="1.5" fill="#fff"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav align-items-center flex-md-row gap-md-3">
                <li class="nav-item mx-2">
                    @if(request()->routeIs('welcome'))
                        <a class="nav-link" href="#menu">Menú</a>
                    @else
                        <a class="nav-link" href="{{ route('welcome') }}#menu">Menú</a>
                    @endif
                </li>

                @guest
                    <li class="nav-item mx-2">
                        <a class="nav-link whitespace-nowrap" href="{{ route('register') }}">Registrarse</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link whitespace-nowrap" href="{{ route('login') }}">Iniciar Sesión</a>
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

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user-circle" style="color:#222;font-size:1.1em;"></i>
                                    Mi Perfil
                                </a>
                            </li>


                            @if(Auth::check() && in_array(Auth::user()->roles_id, [3,4]))
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.menu.index') }}">
                                        <i class="fas fa-cogs" style="color:#222;font-size:1.1em;"></i>
                                        Administrar
                                    </a>
                                </li>
                            @endif

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="dropdown-item d-flex align-items-center gap-2 text-danger fw-semibold" type="submit">
                                        <i class="fas fa-sign-out-alt" style="color:#222;font-size:1.1em;"></i>
                                        Cerrar Sesión
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
