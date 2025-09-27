<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('welcome') }}">Restaurante</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Registrarse</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a></li>
                @endguest

                @auth

                    <li class="nav-item d-flex align-items-center">
                        @php
                            $photo = Auth::user()->photo ? asset('storage/'.Auth::user()->photo) : asset('storage/photos/fotousuario.png');
                        @endphp
                        <a class="nav-link d-flex align-items-center" href="{{ route('profile.show') }}">
                            <img src="{{ $photo }}" width="32" height="32" class="rounded-circle me-2" style="object-fit:cover;">
                            {{ Auth::user()->name }}
                        </a>
                    </li>

                    @if(Auth::check() && in_array(Auth::user()->roles_id, [3,4]))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">Usuarios</a>
                            </li>
                            @endif


                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-link nav-link" type="submit">Cerrar Sesión</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
