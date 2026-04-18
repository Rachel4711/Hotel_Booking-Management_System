@extends('Layouts.admin')
@section('content')

@php
	$extraImages = collect(old('images', $hotel->images ?? []))->take(3)->values();
@endphp

<div class="page-shell">
	<a href="{{ route('hotels.index') }}" class="hotel-back-link" id="hotel-edit-back-link">
		<i class="uil uil-arrow-left"></i>
		Back to Hotel List
	</a>

	<div class="page-header">
		<div>
			<h1 class="page-title">Edit Hotel</h1>
			<p class="page-subtitle">Updating {{ $hotel->name }}</p>
		</div>
	</div>

	<section class="panel-section" style="margin-bottom:14px;">
		@include('Admin.Hotel.partials.gallery', [
			'galleryImages' => $galleryImages,
			'modalId' => 'hotel-edit-gallery-modal',
		])
	</section>

	<section class="panel-section">
		<div id="hotel-no-change-message" class="notice error" style="display:none; margin-bottom:14px;"></div>

		<form method="POST" action="{{ route('hotels.update', $hotel) }}" id="hotel-edit-form" class="hotel-form-grid">
			@csrf
			@method('PUT')

			<div class="hotel-field-group">
				<label class="hotel-field-label" for="name">Hotel Name</label>
				<div class="hotel-input-with-icon">
					<input id="name" type="text" name="name" class="input" value="{{ old('name', $hotel->name) }}" required>
					<i class="uil uil-edit"></i>
				</div>
			</div>

			<div class="hotel-field-group">
				<label class="hotel-field-label" for="state">State</label>
				<div class="hotel-input-with-icon">
					<input id="state" type="text" name="state" class="input" value="{{ old('state', $hotel->state) }}" required>
					<i class="uil uil-edit"></i>
				</div>
			</div>

			<div class="hotel-field-group hotel-span-2">
				<label class="hotel-field-label" for="description">Description</label>
				<div class="hotel-input-with-icon">
					<textarea id="description" name="description" class="input hotel-textarea" required>{{ old('description', $hotel->description) }}</textarea>
					<i class="uil uil-edit"></i>
				</div>
			</div>

			<div class="hotel-field-group hotel-span-2">
				<label class="hotel-field-label" for="address">Address</label>
				<div class="hotel-input-with-icon">
					<textarea id="address" name="address" class="input hotel-textarea" required>{{ old('address', $hotel->address) }}</textarea>
					<i class="uil uil-edit"></i>
				</div>
			</div>

			<div class="hotel-field-group hotel-span-2">
				<label class="hotel-field-label" for="cover_image">Cover Image URL</label>
				<div class="hotel-input-with-icon">
					<input id="cover_image" type="url" name="cover_image" class="input" value="{{ old('cover_image', $hotel->cover_image) }}" required>
					<i class="uil uil-edit"></i>
				</div>
			</div>

			<div class="hotel-field-group hotel-span-2">
				<div class="hotel-images-header">
					<label class="hotel-field-label">Additional Images (up to 3)</label>
					<button type="button" class="form-action secondary" id="add-image-btn">Add Image</button>
				</div>

				<div id="additional-image-wrap" class="hotel-extra-images">
					@foreach ($extraImages as $imageUrl)
						<div class="hotel-input-with-icon hotel-extra-image-item">
							<input type="url" name="images[]" class="input" value="{{ $imageUrl }}" required>
							<i class="uil uil-edit"></i>
							<button type="button" class="hotel-remove-image">
								<i class="uil uil-times"></i>
							</button>
						</div>
					@endforeach
				</div>
			</div>

			<div class="hotel-form-actions hotel-span-2">
				<button type="submit" class="form-action primary">Save Hotel Detail</button>
			</div>
		</form>
	</section>
</div>

<div class="hotel-modal" id="unsaved-modal" aria-hidden="true">
	<div class="hotel-modal-card" role="dialog" aria-modal="true" aria-labelledby="unsaved-modal-title">
		<h3 id="unsaved-modal-title">Unsaved Changes</h3>
		<p>Do you want to save your changes to {{ $hotel->name }}?</p>
		<div class="hotel-modal-actions">
			<button type="button" class="form-action secondary" id="unsaved-no">No</button>
			<button type="button" class="form-action primary" id="unsaved-yes">Yes</button>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
	var form = document.getElementById('hotel-edit-form');
	var backLink = document.getElementById('hotel-edit-back-link');
	var noChangeMessage = document.getElementById('hotel-no-change-message');
	var unsavedModal = document.getElementById('unsaved-modal');
	var unsavedYes = document.getElementById('unsaved-yes');
	var unsavedNo = document.getElementById('unsaved-no');
	var addImageBtn = document.getElementById('add-image-btn');
	var additionalImageWrap = document.getElementById('additional-image-wrap');
	var maxAdditional = 3;
	var backUrl = backLink.getAttribute('href');
	var bypassNoChangeGuard = false;

	function attachRemoveHandler(button) {
		button.addEventListener('click', function () {
			var item = button.closest('.hotel-extra-image-item');
			if (!item) {
				return;
			}

			item.remove();
			toggleAddButton();
		});
	}

	function buildInput() {
		var wrapper = document.createElement('div');
		wrapper.className = 'hotel-input-with-icon hotel-extra-image-item';

		var input = document.createElement('input');
		input.type = 'url';
		input.name = 'images[]';
		input.className = 'input';
		input.required = true;

		var icon = document.createElement('i');
		icon.className = 'uil uil-edit';

		var removeButton = document.createElement('button');
		removeButton.type = 'button';
		removeButton.className = 'hotel-remove-image';
		removeButton.innerHTML = '<i class="uil uil-times"></i>';
		attachRemoveHandler(removeButton);

		wrapper.appendChild(input);
		wrapper.appendChild(icon);
		wrapper.appendChild(removeButton);

		return wrapper;
	}

	function toggleAddButton() {
		addImageBtn.disabled = additionalImageWrap.children.length >= maxAdditional;
	}

	function normalizeValue(field) {
		return field.value.trim().replace(/\r\n/g, '\n');
	}

	function buildSnapshot() {
		var values = [];
		form.querySelectorAll('input[name], textarea[name], select[name]').forEach(function (field) {
			values.push(field.name + '=' + normalizeValue(field));
		});

		return values.join('|');
	}

	var initialSnapshot = buildSnapshot();

	function hasChanges() {
		return buildSnapshot() !== initialSnapshot;
	}

	function closeUnsavedModal() {
		unsavedModal.classList.remove('is-open');
		unsavedModal.setAttribute('aria-hidden', 'true');
	}

	additionalImageWrap.querySelectorAll('.hotel-remove-image').forEach(function (button) {
		attachRemoveHandler(button);
	});

	addImageBtn.addEventListener('click', function () {
		if (additionalImageWrap.children.length >= maxAdditional) {
			return;
		}

		additionalImageWrap.appendChild(buildInput());
		toggleAddButton();
	});

	form.addEventListener('submit', function (event) {
		noChangeMessage.style.display = 'none';

		if (!bypassNoChangeGuard && !hasChanges()) {
			event.preventDefault();
			noChangeMessage.textContent = 'No changes is made to {{ $hotel->name }}';
			noChangeMessage.style.display = 'block';
		}
	});

	backLink.addEventListener('click', function (event) {
		if (!hasChanges()) {
			return;
		}

		event.preventDefault();
		unsavedModal.classList.add('is-open');
		unsavedModal.setAttribute('aria-hidden', 'false');
	});

	unsavedNo.addEventListener('click', function () {
		window.location.href = backUrl;
	});

	unsavedYes.addEventListener('click', function () {
		closeUnsavedModal();
		bypassNoChangeGuard = true;
		form.submit();
	});

	unsavedModal.addEventListener('click', function (event) {
		if (event.target === unsavedModal) {
			closeUnsavedModal();
		}
	});

	toggleAddButton();
});
</script>



@endsection