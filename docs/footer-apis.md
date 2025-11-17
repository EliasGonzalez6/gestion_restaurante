# Documentación: APIs en el Footer

## Objetivo
Explicar cómo y dónde se integran APIs externas en el footer del proyecto.

---
## 1. Ubicación
- Archivo principal: `resources/views/partials/footer.blade.php`
- Puede incluirse en otras vistas mediante `@include('partials.footer')` (ejemplo: `welcome.blade.php`).

---
## 2. APIs Usadas
- **Google Maps Embed**: Muestra ubicación del restaurante.
  - `<iframe src="https://www.google.com/maps/embed?..." ...></iframe>`
- **WhatsApp API**: Botón para abrir chat directo con el restaurante.
  - `<a href="https://wa.me/549XXXXXXXXXX" ...>WhatsApp</a>`
- **Instagram/Facebook**: Enlaces a redes sociales (no API directa, solo links).
- **Correo**: Link mailto para contacto rápido.

---
## 3. Implementación
- El footer contiene los iframes, enlaces y botones con los links a las APIs.
- No hay lógica backend ni AJAX, todo es integración directa en HTML.
- Ejemplo:
  ```html
  <a href="https://wa.me/549XXXXXXXXXX" target="_blank">
    <i class="fab fa-whatsapp"></i> WhatsApp
  </a>
  <iframe src="https://www.google.com/maps/embed?..." width="300" height="200"></iframe>
  ```
- Los íconos se muestran con FontAwesome.

---
## 4. Archivos Relacionados
- `resources/views/partials/footer.blade.php` (principal)
- `resources/views/welcome.blade.php` (donde se incluye)
- `public/css/footer.css` (estilos)

---
## 5. Resumen
Las APIs en el footer se integran mediante enlaces y iframes, sin lógica backend. Permiten contacto directo y mostrar ubicación.

---
Fin de documento.
