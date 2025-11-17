@extends('layouts.app')

@section('content')

<div class="edit-profile-title">Editar Perfil</div>
<div class="edit-profile-subtitle">Actualiza tu información personal</div>
<div class="edit-profile-container">
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="edit-profile-form">
        @csrf
        @method('PUT')
        <!-- Alerta informativa -->
        <div class="edit-profile-alert">
            <i class="fa fa-info-circle"></i>
            <div><strong>Importante:</strong> Asegúrate de que toda la información sea correcta antes de guardar los cambios.</div>
        </div>
        <!-- Sección foto de perfil -->
        @php
            $currentPhoto = $user->photo 
                ? asset('storage/'.$user->photo) 
                : asset('storage/photos/fotousuario.png');
        @endphp
        <div class="profile-photo-section" style="display:flex; align-items:center; gap:1.25rem; flex-wrap:wrap;">
            <div class="photo-preview-wrapper" style="position:relative; width:150px; height:150px;">
                <img id="photoPreview"
                     src="{{ $currentPhoto }}"
                     alt="Foto de perfil"
                     style="width:150px; height:150px; object-fit:cover; border-radius:50%; border:2px solid var(--dorado); box-shadow:0 2px 8px #d4af3722; transition:opacity .25s;" />
                <div class="photo-overlay" onclick="document.getElementById('photoInput').click()" 
                     style="position:absolute; inset:0; display:flex; flex-direction:column; justify-content:center; align-items:center; background:#0000006b; color:#fff; border-radius:50%; font-size:.8rem; gap:4px; opacity:0; cursor:pointer; transition:opacity .25s;">
                    <i class="fa fa-camera"></i>
                    <span>Cambiar</span>
                </div>
            </div>
            <div class="photo-actions" style="display:flex; flex-direction:column; gap:.5rem;">
                <button type="button" class="photo-upload-btn" onclick="document.getElementById('photoInput').click()" 
                        style="background:var(--dorado); color:#1c1c1c; border:none; padding:.6rem 1rem; border-radius:6px; font-weight:600; display:inline-flex; align-items:center; gap:.5rem; cursor:pointer; box-shadow:0 2px 6px #d4af3744;">
                    <i class="fa fa-upload"></i> Seleccionar Foto
                </button>
                <small style="font-size:.7rem; color:#666;">Formatos permitidos: JPG, PNG, GIF. Máx 10MB.</small>
                <input type="file" name="photo" id="photoInput" accept="image/*" style="display:none;">
                <div id="photoError" style="display:none; color:#c0392b; font-size:.7rem; font-weight:600;"></div>
            </div>
        </div>
        <!-- Información Personal -->
        <div class="edit-profile-divider"></div>
        <div class="edit-profile-section-title"><i class="fa fa-user"></i> Información Personal</div>
        <div class="form-row">
            <div>
                <label for="name">Nombre Completo</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div>
                <label for="email">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
        </div>
        <div class="form-row">
            <div>
                <label for="phone">Teléfono</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}">
            </div>
            <div>
                <label for="dni">DNI</label>
                <input type="text" name="dni" value="{{ old('dni', $user->dni) }}" disabled>
            </div>
        </div>
        <div class="form-row-single">
            <label for="address">Dirección Completa</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}">
        </div>
        <!-- Seguridad -->
        <div class="edit-profile-divider"></div>
        <div class="edit-profile-section-title"><i class="fa fa-lock"></i> Seguridad</div>
        <div class="form-row">
            <div>
                <label for="current_password">Contraseña Actual</label>
                <input type="password" name="current_password" placeholder="Dejar en blanco si no deseas cambiar">
            </div>
            <div>
                <label for="password">Nueva Contraseña</label>
                <input type="password" name="password" placeholder="Nueva contraseña">
            </div>
        </div>
        <div class="form-row-single">
            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
            <input type="password" name="password_confirmation" placeholder="Confirmar nueva contraseña">
        </div>
        <!-- Acciones -->
        <div class="form-actions">
            <button type="submit" class="btn-save"><i class="fa fa-save"></i> Guardar Cambios</button>
            <form action="{{ route('profile.show') }}" method="get" style="display:inline;">
                <button type="submit" class="btn-cancel"><i class="fa fa-times"></i> Cancelar</button>
            </form>
        </div>
    </form>
<script>
// Hover overlay
document.addEventListener('DOMContentLoaded', () => {
  const overlay = document.querySelector('.photo-overlay');
  if (overlay) {
    const wrapper = overlay.parentElement;
    wrapper.addEventListener('mouseenter', () => overlay.style.opacity = '1');
    wrapper.addEventListener('mouseleave', () => overlay.style.opacity = '0');
  }
  const input = document.getElementById('photoInput');
  if (input) input.addEventListener('change', previewPhoto);
});

function previewPhoto(e) {
  const file = e.target.files && e.target.files[0];
  const errorBox = document.getElementById('photoError');
  if (!file) return;
  // Validaciones básicas
  const validTypes = ['image/jpeg','image/png','image/gif'];
  if (!validTypes.includes(file.type)) {
      if (errorBox) {
          errorBox.textContent = 'Formato no permitido. Usa JPG, PNG o GIF.';
          errorBox.style.display = 'block';
      }
      e.target.value = '';
      return;
  }
  if (file.size > 10 * 1024 * 1024) { // 10MB
      if (errorBox) {
          errorBox.textContent = 'El archivo excede 10MB.';
          errorBox.style.display = 'block';
      }
      e.target.value = '';
      return;
  }
  if (errorBox) errorBox.style.display = 'none';
  const reader = new FileReader();
  reader.onload = function(ev) {
      const img = document.getElementById('photoPreview');
      if (!img) return;
      img.style.opacity = '0.3';
      img.src = ev.target.result;
      setTimeout(() => img.style.opacity = '1', 180);
  }
  reader.readAsDataURL(file);
}
</script>
</div>
</div>
@endsection
