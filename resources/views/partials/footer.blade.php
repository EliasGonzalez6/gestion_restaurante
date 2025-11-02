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

            {{-- Columna 3: Información de contacto y Clima --}}
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
                
                {{-- Widget del Clima --}}
                <div class="weather-widget mt-3">
                    <h6 class="footer-heading mb-2">Clima en Buenos Aires</h6>
                    <div id="weather-info" class="weather-info">
                        <div class="weather-loading">
                            <i class="fas fa-spinner fa-spin"></i> Cargando...
                        </div>
                    </div>
                </div>
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

        {{-- Mapa de Ubicación --}}
        <div class="row mb-4">
            <div class="col-12">
                <h6 class="footer-heading text-center mb-3">¿Cómo llegar?</h6>
                <div class="map-container">
                    <iframe 
                        src="https://maps.google.com/maps?q=Re+Chévere+Restaurante,+Av.+S.+Martín+1518,+C1416+CABA&t=&z=16&ie=UTF8&iwloc=&output=embed"
                        width="100%" 
                        height="300" 
                        style="border:0; border-radius: 12px;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

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

{{-- Script para API del Clima --}}
<script>
    // API del clima usando Open-Meteo (100% gratuita, sin API key necesaria)
    async function fetchWeather() {
        try {
            const latitude = -34.5892;
            const longitude = -58.4500;
            const url = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current_weather=true&timezone=America/Argentina/Buenos_Aires`;
            
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.current_weather) {
                const temp = Math.round(data.current_weather.temperature);
                const weatherCode = data.current_weather.weathercode;
                const windSpeed = Math.round(data.current_weather.windspeed);
                
                // Mapeo de códigos de clima a iconos y descripciones
                const weatherInfo = getWeatherInfo(weatherCode);
                
                const weatherHTML = `
                    <div class="weather-display">
                        <div class="weather-icon">
                            <i class="${weatherInfo.icon}"></i>
                        </div>
                        <div class="weather-details">
                            <div class="weather-temp">${temp}°C</div>
                            <div class="weather-desc">${weatherInfo.description}</div>
                            <div class="weather-wind"><i class="fas fa-wind"></i> ${windSpeed} km/h</div>
                        </div>
                    </div>
                `;
                
                document.getElementById('weather-info').innerHTML = weatherHTML;
            }
        } catch (error) {
            console.error('Error al obtener el clima:', error);
            document.getElementById('weather-info').innerHTML = `
                <div class="weather-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    No disponible
                </div>
            `;
        }
    }
    
    function getWeatherInfo(code) {
        const weatherCodes = {
            0: { icon: 'fas fa-sun', description: 'Despejado' },
            1: { icon: 'fas fa-cloud-sun', description: 'Parcialmente nublado' },
            2: { icon: 'fas fa-cloud-sun', description: 'Parcialmente nublado' },
            3: { icon: 'fas fa-cloud', description: 'Nublado' },
            45: { icon: 'fas fa-smog', description: 'Neblina' },
            48: { icon: 'fas fa-smog', description: 'Niebla' },
            51: { icon: 'fas fa-cloud-rain', description: 'Llovizna ligera' },
            53: { icon: 'fas fa-cloud-rain', description: 'Llovizna moderada' },
            55: { icon: 'fas fa-cloud-showers-heavy', description: 'Llovizna densa' },
            61: { icon: 'fas fa-cloud-rain', description: 'Lluvia ligera' },
            63: { icon: 'fas fa-cloud-showers-heavy', description: 'Lluvia moderada' },
            65: { icon: 'fas fa-cloud-showers-heavy', description: 'Lluvia intensa' },
            71: { icon: 'fas fa-snowflake', description: 'Nevada ligera' },
            73: { icon: 'fas fa-snowflake', description: 'Nevada moderada' },
            75: { icon: 'fas fa-snowflake', description: 'Nevada intensa' },
            80: { icon: 'fas fa-cloud-rain', description: 'Chubascos ligeros' },
            81: { icon: 'fas fa-cloud-showers-heavy', description: 'Chubascos moderados' },
            82: { icon: 'fas fa-cloud-showers-heavy', description: 'Chubascos intensos' },
            95: { icon: 'fas fa-bolt', description: 'Tormenta' },
            96: { icon: 'fas fa-bolt', description: 'Tormenta con granizo' },
            99: { icon: 'fas fa-bolt', description: 'Tormenta severa' }
        };
        
        return weatherCodes[code] || { icon: 'fas fa-cloud', description: 'Clima desconocido' };
    }
    
    // Cargar clima al cargar la página
    document.addEventListener('DOMContentLoaded', fetchWeather);
    
    // Actualizar cada 30 minutos
    setInterval(fetchWeather, 30 * 60 * 1000);
</script>
