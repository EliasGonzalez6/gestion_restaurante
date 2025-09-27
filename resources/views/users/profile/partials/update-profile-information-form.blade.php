<div class="mb-3 text-center">
	@php
		$photo = $user->photo ? asset('storage/'.$user->photo) : asset('storage/photos/fotousuario.png');
	@endphp
	<img id="profilePhotoPreview" src="{{ $photo }}" class="rounded-circle mb-2" width="120" height="120" style="object-fit:cover;">
	<input type="file" name="photo" class="form-control mt-2" id="profilePhotoInput" accept="image/*" onchange="previewProfilePhoto(event)">
</div>
<script>
function previewProfilePhoto(event) {
	const input = event.target;
	const preview = document.getElementById('profilePhotoPreview');
	if (input.files && input.files[0]) {
		const reader = new FileReader();
		reader.onload = function(e) {
			preview.src = e.target.result;
		}
		reader.readAsDataURL(input.files[0]);
	}
}
</script>
@include('profile.partials.update-profile-information-form')
