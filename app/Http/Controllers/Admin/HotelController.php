<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
	public function index(Request $request): View
	{
		$search = trim((string) $request->query('search', ''));
		$state = trim((string) $request->query('state', 'All'));

		$states = Hotel::query()
			->select('state')
			->whereNotNull('state')
			->where('state', '!=', '')
			->distinct()
			->orderBy('state')
			->pluck('state')
			->values();

		$hotels = Hotel::query()
			->when($state !== '' && $state !== 'All', function ($query) use ($state) {
				$query->where('state', $state);
			})
			->when($search !== '', function ($query) use ($search) {
				$normalizedSearch = mb_strtolower($search);

				$query->where(function ($subQuery) use ($normalizedSearch) {
					$subQuery->whereRaw('LOWER(name) LIKE ?', ['%' . $normalizedSearch . '%'])
						->orWhereRaw('LOWER(address) LIKE ?', ['%' . $normalizedSearch . '%'])
						->orWhereRaw('LOWER(state) LIKE ?', ['%' . $normalizedSearch . '%']);
				});
			})
			->orderBy('name')
			->paginate(15)
			->withQueryString();

		return view('Admin.Hotel.index', [
			'hotels' => $hotels,
			'states' => $states,
			'search' => $search,
			'state' => $state,
		]);
	}

	public function create(): View
	{
		return view('Admin.Hotel.create');
	}

	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'description' => ['required', 'string'],
			'address' => ['required', 'string', 'max:255'],
			'state' => ['required', 'string', 'max:50'],
			'cover_image' => ['required', 'url', 'max:2000'],
			'images' => ['nullable', 'array', 'max:3'],
			'images.*' => ['required', 'url', 'max:2000'],
		]);

		[$coverImage, $images] = $this->normalizeImages(
			$validated['cover_image'],
			$validated['images'] ?? []
		);

		Hotel::create([
			'name' => $validated['name'],
			'description' => $validated['description'],
			'address' => $validated['address'],
			'state' => $validated['state'],
			'cover_image' => $coverImage,
			'images' => $images,
			'click_count' => 0,
		]);

		return redirect()
			->route('hotels.index')
			->with('success', 'Hotel added successfully.');
	}

	public function show(Hotel $hotel): View
	{
		return view('Admin.Hotel.show', [
			'hotel' => $hotel,
			'galleryImages' => $this->buildGalleryImages($hotel),
		]);
	}

	public function edit(Hotel $hotel): View
	{
		return view('Admin.Hotel.edit', [
			'hotel' => $hotel,
			'galleryImages' => $this->buildGalleryImages($hotel),
		]);
	}

	public function update(Request $request, Hotel $hotel): RedirectResponse
	{
		$validated = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'description' => ['required', 'string'],
			'address' => ['required', 'string', 'max:255'],
			'state' => ['required', 'string', 'max:50'],
			'cover_image' => ['required', 'url', 'max:2000'],
			'images' => ['nullable', 'array', 'max:3'],
			'images.*' => ['required', 'url', 'max:2000'],
		]);

		[$coverImage, $images] = $this->normalizeImages(
			$validated['cover_image'],
			$validated['images'] ?? []
		);

		$hotel->fill([
			'name' => $validated['name'],
			'description' => $validated['description'],
			'address' => $validated['address'],
			'state' => $validated['state'],
			'cover_image' => $coverImage,
			'images' => $images,
		]);

		$hotel->save();

		return redirect()
			->route('hotels.index')
			->with('success', 'Hotel updated successfully.');
	}

	public function destroy(Hotel $hotel): RedirectResponse
	{
		$hotel->delete();

		return redirect()
			->route('hotels.index')
			->with('success', 'Hotel deleted successfully.');
	}

	/**
	 * Build gallery list with cover image first and unique additional URLs.
	 */
	private function buildGalleryImages(Hotel $hotel): array
	{
		$images = is_array($hotel->images) ? $hotel->images : [];
		$combined = array_values(array_filter(array_merge([$hotel->cover_image], $images)));

		return array_values(array_unique($combined));
	}

	/**
	 * Normalize image payload to satisfy storage rules.
	 */
	private function normalizeImages(string $coverInput, array $additionalInputs): array
	{
		$coverImage = trim($coverInput);

		$additionalImages = collect($additionalInputs)
			->map(fn ($url) => trim((string) $url))
			->filter(fn ($url) => $url !== '')
			->take(3)
			->values()
			->all();

		if (count($additionalImages) > 0) {
			// Multiple image case: keep first input as cover and store others in images.
			return [$coverImage, $additionalImages];
		}

		return [$coverImage, [$coverImage]];
	}
}
