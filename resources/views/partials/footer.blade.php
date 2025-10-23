<footer class="footer-custom">
    <div class="container">
        <div class="row g-4">
            {{-- Columna 1: Logo y descripción --}}
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <div class="footer-brand mb-3">
                    <img src="{{ asset('storage/photos/Logo.png') }}" alt="Re Chévere" height="50" class="mb-3">
                    <h5 class="footer-title">Re Chévere Digital</h5>
                </div>
                <p class="footer-description">
                    Sabores auténticos que te hacen sentir en casa. Tradición venezolana con un toque digital.
                </p>
                <div class="footer-social mt-3">
                    <a href="https://www.facebook.com/profile.php?id=100094690656573" class="social-link" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/rechevererestaurante/" class="social-link" aria-label="Instagram" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/5491130856062" class="social-link" aria-label="WhatsApp" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            {{-- Columna 2: Enlaces rápidos --}}
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h6 class="footer-heading">Enlaces Rápidos</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('welcome') }}">Inicio</a></li>
                    <li><a href="{{ route('welcome') }}#menu">Menú</a></li>
                    @auth
                        <li><a href="{{ route('profile.show') }}">Mi Perfil</a></li>
                    @else
                        <li><a href="{{ route('register') }}">Registrarse</a></li>
                        <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                    @endauth
                </ul>
            </div>

            {{-- Columna 3: Información de contacto --}}
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h6 class="footer-heading">Contacto</h6>
                <ul class="footer-contact">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Av. San Martín 1518, CABA</span>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <span>+54 9 11 3085-6062</span>
                    </li>
                </ul>
            </div>

            {{-- Columna 4: Horarios --}}
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">Horarios</h6>
                <ul class="footer-schedule">
                    <li>
                        <span class="day">Lunes - Viernes</span>
                        <span class="time">12:00 PM - 11:00 PM</span>
                    </li>
                    <li>
                        <span class="day">Sábado</span>
                        <span class="time">12:00 PM - 12:00 AM</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Línea divisoria --}}
        <hr class="footer-divider">

        {{-- Copyright --}}
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <p class="footer-copyright mb-0">
                        &copy; {{ date('Y') }} Re Chévere Digital. Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="footer-legal mb-0">
                        <li><a href="#">Términos de Servicio</a></li>
                        <li><a href="#">Política de Privacidad</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
