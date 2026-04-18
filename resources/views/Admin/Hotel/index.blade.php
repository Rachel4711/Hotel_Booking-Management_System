@extends('Layouts.admin')
@section('content')

@php
	$stateOptions = collect(['All'])->merge($states ?? collect());
	$startNo = ($hotels->currentPage() - 1) * $hotels->perPage();
@endphp

<div class="page-shell">
	<div class="page-header">
		<div>
			<h1 class="page-title">Hotel List</h1>
			<p class="page-subtitle">Manage hotel records and details</p>
		</div>
	</div>

	<section class="panel-section" style="margin-bottom:14px;">
		<form method="GET" action="{{ route('hotels.index') }}" class="hotel-filter-row">
			<div class="hotel-filter-left">
				<input
					type="text"
					name="search"
					class="input"
					value="{{ $search }}"
					placeholder="Search by hotel name, address, or state"
				>

				<select name="state" class="input">
					@foreach ($stateOptions as $stateOption)
						<option value="{{ $stateOption }}" {{ $state === $stateOption ? 'selected' : '' }}>
							{{ $stateOption }}
						</option>
					@endforeach
				</select>

				<button type="submit" class="form-action primary">Search</button>
				<a href="{{ route('hotels.index') }}" class="form-action secondary">Reset</a>
			</div>

			<div class="hotel-filter-right">
				<a href="{{ route('hotels.create') }}" class="form-action secondary">Add Hotel</a>
			</div>
		</form>
	</section>

	<section class="panel-section">
		<div class="table-wrap">
			<table class="table-modern">
				<thead>
					<tr>
						<th>Hotel ID</th>
						<th>Hotel Name</th>
						<th>Address</th>
						<th>State</th>
						<th>View</th>
						<th>Edit</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($hotels as $hotel)
						<tr>
							{{-- Keep numbering continuous across pages without exposing primary key --}}
							<td>{{ $startNo + $loop->iteration }}</td>
							<td>{{ $hotel->name }}</td>
							<td>{{ $hotel->address }}</td>
							<td><span class="badge">{{ $hotel->state }}</span></td>
							<td>
								<a href="{{ route('hotels.show', $hotel) }}" class="hotel-icon-link" aria-label="View {{ $hotel->name }}">
									<i class="uil uil-eye"></i>
								</a>
							</td>
							<td>
								<a href="{{ route('hotels.edit', $hotel) }}" class="hotel-icon-link edit" aria-label="Edit {{ $hotel->name }}">
									<i class="uil uil-edit"></i>
								</a>
							</td>
							<td>
								<button
									type="button"
									class="hotel-icon-link delete js-delete-btn"
									data-form-id="delete-hotel-{{ $hotel->id }}"
									data-hotel-name="{{ $hotel->name }}"
									aria-label="Delete {{ $hotel->name }}"
								>
									<i class="uil uil-trash-alt"></i>
								</button>
								<form id="delete-hotel-{{ $hotel->id }}" method="POST" action="{{ route('hotels.destroy', $hotel) }}" style="display:none;">
									@csrf
									@method('DELETE')
								</form>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="7">No hotels found.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<div style="margin-top:14px;">
			<p style="margin:0; color:#64748b; font-size:14px;">
				Showing {{ $hotels->firstItem() ?? 0 }} to {{ $hotels->lastItem() ?? 0 }} of {{ $hotels->total() }} results
			</p>
		</div>
	</section>
</div>

<div class="hotel-modal" id="delete-modal" aria-hidden="true">
	<div class="hotel-modal-card" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
		<h3 id="delete-modal-title">Delete Hotel</h3>
		<p id="delete-modal-text">Are you sure?</p>
		<div class="hotel-modal-actions">
			<button type="button" class="form-action secondary" id="delete-modal-cancel">No</button>
			<button type="button" class="form-action btn-delete" id="delete-modal-confirm">Yes</button>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
	var deleteModal = document.getElementById('delete-modal');
	var deleteModalText = document.getElementById('delete-modal-text');
	var deleteCancel = document.getElementById('delete-modal-cancel');
	var deleteConfirm = document.getElementById('delete-modal-confirm');
	var activeDeleteForm = null;

	function closeDeleteModal() {
		deleteModal.classList.remove('is-open');
		deleteModal.setAttribute('aria-hidden', 'true');
		activeDeleteForm = null;
	}

	document.querySelectorAll('.js-delete-btn').forEach(function (button) {
		button.addEventListener('click', function () {
			var formId = button.getAttribute('data-form-id');
			var hotelName = button.getAttribute('data-hotel-name');
			activeDeleteForm = document.getElementById(formId);
			deleteModalText.textContent = 'Are you sure you want to delete ' + hotelName + '?';
			deleteModal.classList.add('is-open');
			deleteModal.setAttribute('aria-hidden', 'false');
		});
	});

	deleteCancel.addEventListener('click', closeDeleteModal);
	deleteModal.addEventListener('click', function (event) {
		if (event.target === deleteModal) {
			closeDeleteModal();
		}
	});

	deleteConfirm.addEventListener('click', function () {
		if (activeDeleteForm) {
			activeDeleteForm.submit();
		}
	});
});
</script>



@endsection