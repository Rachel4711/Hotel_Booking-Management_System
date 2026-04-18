@extends('Layouts.admin')
@section('content')

<div class="page-shell">
	<a href="{{ route('hotels.index') }}" class="hotel-back-link">
		<i class="uil uil-arrow-left"></i>
		Back to Hotel List
	</a>

	<div class="page-header">
		<div>
			<h1 class="page-title">Add Hotel</h1>
			<p class="page-subtitle">Create a new hotel record</p>
		</div>
	</div>

	<section class="panel-section">
		<form method="POST" action="{{ route('hotels.store') }}" id="hotel-create-form" class="hotel-form-grid">
			@csrf

			<div class="hotel-field-group">
				<label class="hotel-field-label" for="name">Hotel Name</label>
				<div class="hotel-input-with-icon">
					<input id="name" type="text" name="name" class="input" value="{{ old('name') }}" required>
					<i class="uil uil-hotel"></i>
				</div>
			</div>

			<div class="hotel-field-group">
				<label class="hotel-field-label" for="state">State</label>
				<div class="hotel-input-with-icon">
					<input id="state" type="text" name="state" class="input" value="{{ old('state') }}" required>
					<i class="uil uil-map-marker"></i>
				</div>
			</div>

			<div class="hotel-field-group hotel-span-2">
				<label class="hotel-field-label" for="description">Description</label>
				<div class="hotel-input-with-icon">
					<textarea id="description" name="description" class="input hotel-textarea" required>{{ old('description') }}</textarea>
					<i class="uil uil-align-left"></i>
				</div>
			</div>

			<div class="hotel-field-group hotel-span-2">
				<label class="hotel-field-label" for="address">Address</label>
				<div class="hotel-input-with-icon">
					<textarea id="address" name="address" class="input hotel-textarea" required>{{ old('address') }}</textarea>
					<i class="uil uil-location-point"></i>
				</div>
			</div>

			<div class="hotel-field-group hotel-span-2">
				<label class="hotel-field-label" for="cover_image">Cover Image URL (First Image)</label>
				<div class="hotel-input-with-icon">
					<input id="cover_image" type="url" name="cover_image" class="input" value="{{ old('cover_image') }}" required>
					<i class="uil uil-image"></i>
				</div>
			</div>

			<div class="hotel-field-group hotel-span-2">
				<div class="hotel-images-header">
					<label class="hotel-field-label">Additional Images (up to 3)</label>
					<button type="button" class="form-action secondary" id="add-image-btn">Add Image</button>
				</div>

				<div id="additional-image-wrap" class="hotel-extra-images">
					@php
						$oldImages = old('images', []);
					@endphp

					@foreach ($oldImages as $imageUrl)
						<div class="hotel-input-with-icon hotel-extra-image-item">
							<input type="url" name="images[]" class="input" value="{{ $imageUrl }}" required>
							<i class="uil uil-image-plus"></i>
						</div>
					@endforeach
				</div>
			</div>

			<div class="hotel-form-actions hotel-span-2">
				<button type="submit" class="form-action primary">Add Hotel</button>
				<a href="{{ route('hotels.index') }}" class="form-action secondary">Cancel</a>
			</div>
		</form>
	</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
	var addImageBtn = document.getElementById('add-image-btn');
	var additionalImageWrap = document.getElementById('additional-image-wrap');
	var maxAdditional = 3;

	function buildInput() {
		var wrapper = document.createElement('div');
		wrapper.className = 'hotel-input-with-icon hotel-extra-image-item';

		var input = document.createElement('input');
		input.type = 'url';
		input.name = 'images[]';
		input.className = 'input';
		input.required = true;

		var icon = document.createElement('i');
		icon.className = 'uil uil-image-plus';

		var removeButton = document.createElement('button');
		removeButton.type = 'button';
		removeButton.className = 'hotel-remove-image';
		removeButton.innerHTML = '<i class="uil uil-times"></i>';
		removeButton.addEventListener('click', function () {
			wrapper.remove();
			toggleAddButton();
		});

		wrapper.appendChild(input);
		wrapper.appendChild(icon);
		wrapper.appendChild(removeButton);

		return wrapper;
	}

	function toggleAddButton() {
		addImageBtn.disabled = additionalImageWrap.children.length >= maxAdditional;
	}

	addImageBtn.addEventListener('click', function () {
		if (additionalImageWrap.children.length >= maxAdditional) {
			return;
		}

		additionalImageWrap.appendChild(buildInput());
		toggleAddButton();
	});

	additionalImageWrap.querySelectorAll('.hotel-extra-image-item').forEach(function (item) {
		var removeButton = document.createElement('button');
		removeButton.type = 'button';
		removeButton.className = 'hotel-remove-image';
		removeButton.innerHTML = '<i class="uil uil-times"></i>';
		removeButton.addEventListener('click', function () {
			item.remove();
			toggleAddButton();
		});
		item.appendChild(removeButton);
	});

	toggleAddButton();
});
</script>



@endsection