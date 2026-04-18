@extends('Layouts.user')

@section('title', 'Home - LuxeHotel')

@php
    $heroImage = 'https://plus.unsplash.com/premium_photo-1661964071015-d97428970584?fm=jpg&q=60&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8aG90ZWx8ZW58MHx8MHx8fDA%3D';

    $buildHotelImage = function ($hotel) {
        $coverImage = $hotel->cover_image ?? $hotel->image_url ?? '';

        if ($coverImage === '') {
            return 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=900&q=80';
        }

        if (filter_var($coverImage, FILTER_VALIDATE_URL)) {
            return $coverImage;
        }

        return route('photo.show', $coverImage);
    };

    $activeState = $selectedState ?? 'All';
    $stateFilters = collect(['All'])->merge($hotelStates ?? collect());
@endphp

@push('styles')
<style>
    .home-shell {
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    .hero-section {
        position: relative;
        min-height: 720px;
        border-radius: 28px;
        overflow: hidden;
        background: #1f1b18;
        box-shadow: 0 24px 50px rgba(40, 30, 24, 0.18);
    }

    .hero-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(10, 8, 7, 0.72), rgba(10, 8, 7, 0.38));
        z-index: 1;
    }

    .hero-background {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transform: scale(1.04);
    }

    .hero-content {
        position: relative;
        z-index: 2;
        min-height: 720px;
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(320px, 420px);
        gap: 30px;
        align-items: center;
        padding: 48px;
    }

    .hero-copy {
        color: #fff;
        max-width: 760px;
    }

    .hero-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .hero-copy h1 {
        margin: 18px 0 14px;
        font-size: clamp(2.5rem, 5vw, 4.8rem);
        line-height: 0.98;
        letter-spacing: -0.04em;
    }

    .hero-copy p {
        margin: 0;
        max-width: 620px;
        color: rgba(255, 255, 255, 0.86);
        font-size: 1.08rem;
        line-height: 1.7;
    }

    .hero-search-wrap {
        margin-top: 34px;
        display: flex;
        justify-content: center;
    }

    .hero-search {
        width: min(100%, 760px);
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 12px;
        padding: 12px;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.35);
        box-shadow: 0 20px 42px rgba(0, 0, 0, 0.18);
    }

    .hero-search input {
        width: 100%;
        border: 0;
        outline: 0;
        background: transparent;
        padding: 16px 18px;
        font-size: 1rem;
        color: #2c2420;
    }

    .hero-search button {
        border: 0;
        border-radius: 16px;
        padding: 0 24px;
        background: linear-gradient(135deg, #7e6552, #4a3c31);
        color: #fff;
        font-weight: 800;
        cursor: pointer;
        min-height: 56px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hero-search button:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(74, 60, 49, 0.24);
    }

    .suggestion-panel {
        position: relative;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 28px;
        padding: 22px;
        color: #fff;
        backdrop-filter: blur(16px);
        box-shadow: 0 18px 42px rgba(0, 0, 0, 0.18);
    }

    .suggestion-panel h2 {
        margin: 0;
        font-size: 1.15rem;
        letter-spacing: -0.02em;
    }

    .suggestion-panel p {
        margin: 8px 0 18px;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
        font-size: 0.95rem;
    }

    .suggestion-list {
        display: grid;
        gap: 14px;
    }

    .suggestion-card {
        display: grid;
        grid-template-columns: 72px minmax(0, 1fr);
        gap: 14px;
        align-items: center;
        padding: 12px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .suggestion-card img {
        width: 72px;
        height: 72px;
        border-radius: 16px;
        object-fit: cover;
    }

    .suggestion-card strong {
        display: block;
        font-size: 1rem;
        line-height: 1.3;
        margin-bottom: 6px;
    }

    .suggestion-card small {
        color: rgba(255, 255, 255, 0.72);
    }

    .section-card {
        background: rgba(255, 255, 255, 0.78);
        border: 1px solid rgba(126, 101, 82, 0.14);
        border-radius: 28px;
        box-shadow: 0 18px 36px rgba(74, 60, 49, 0.08);
        padding: 24px;
        backdrop-filter: blur(14px);
    }

    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .filter-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 16px;
        border-radius: 999px;
        border: 1px solid rgba(126, 101, 82, 0.18);
        background: rgba(255, 255, 255, 0.84);
        color: #4a3c31;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .filter-chip:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(74, 60, 49, 0.08);
    }

    .filter-chip.active {
        background: linear-gradient(135deg, #7e6552, #4a3c31);
        border-color: transparent;
        color: #fff;
    }

    .hotel-grid {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 20px;
    }

    .hotel-card {
        grid-column: span 4;
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 16px 30px rgba(74, 60, 49, 0.08);
        border: 1px solid rgba(126, 101, 82, 0.1);
        transition: transform 0.22s ease, box-shadow 0.22s ease;
        display: flex;
        flex-direction: column;
        min-height: 100%;
    }

    .hotel-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 40px rgba(74, 60, 49, 0.14);
    }

    .hotel-image {
        aspect-ratio: 3 / 2;
        width: 100%;
        object-fit: cover;
        background: #eadfd5;
    }

    .hotel-details {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 16px;
        padding: 18px;
        flex: 1;
    }

    .hotel-meta {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .hotel-meta h3 {
        margin: 0;
        font-size: 1.05rem;
        color: #2a2420;
    }

    .hotel-meta p {
        margin: 0;
        color: #7e6552;
        line-height: 1.6;
    }

    .hotel-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .book-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 18px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 800;
        background: linear-gradient(135deg, #7e6552, #4a3c31);
        color: #fff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .book-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 18px rgba(74, 60, 49, 0.18);
    }

    .empty-state {
        grid-column: 1 / -1;
        padding: 34px;
        border-radius: 22px;
        border: 1px dashed rgba(126, 101, 82, 0.28);
        color: #7e6552;
        background: rgba(255, 255, 255, 0.7);
        text-align: center;
    }

    @media (max-width: 1100px) {
        .hero-content {
            grid-template-columns: 1fr;
        }

        .hero-section {
            min-height: auto;
        }

        .hero-content {
            min-height: auto;
        }

        .hotel-card {
            grid-column: span 6;
        }
    }

    @media (max-width: 768px) {
        .hero-content {
            padding: 24px;
        }

        .hero-search {
            grid-template-columns: 1fr;
        }

        .hero-search button {
            width: 100%;
        }

        .section-card {
            padding: 18px;
        }

        .hotel-card {
            grid-column: 1 / -1;
        }
    }
</style>
@endpush

@section('content')
<div class="home-shell">
    {{-- Hero section: background, search, and AI-style recommendations. --}}
    <section class="hero-section">
        <img class="hero-background" src="{{ $heroImage }}" alt="Luxury hotel reception background">

        <div class="hero-content">
            <div class="hero-copy">
                <h1>Find your next stay with a smarter hotel homepage.</h1>
                <p>Search by hotel name or state, discover the latest options, and get personalized hotel suggestions based on your booking preferences.</p>

                <form class="hero-search-wrap" method="GET" action="{{ route('home') }}">
                    <div class="hero-search">
                        <input type="hidden" name="state" value="{{ $activeState }}">
                        <input
                            type="text"
                            name="search"
                            value="{{ $searchTerm ?? '' }}"
                            placeholder="Search by hotel name or state (e.g. Johor, Mersing)"
                            aria-label="Search hotels"
                        >
                        <button type="submit">Search Hotels</button>
                    </div>
                </form>
            </div>

            <aside class="suggestion-panel" aria-label="AI hotel suggestions">
                <h2>AI Suggestions</h2>
                <p>No idea which hotel to choose? This panel suggest the hot-pick mix for you. Recommended for you based on your preferences and the hot-pick.</p>

                <div class="suggestion-list">
                    @forelse ($recommendations as $index => $hotel)
                        <a class="suggestion-card" href="{{ route('user.booking.create', $hotel->id) }}" style="text-decoration:none; color:inherit;">
                            <img src="{{ $buildHotelImage($hotel) }}" alt="{{ $hotel->name }}">
                            <div>
                                <strong>{{ $index + 1 }}. {{ $hotel->name }}</strong>
                                <small>{{ $hotel->state ?: 'Featured stay' }} · {{ $hotel->click_count > 0 ? $hotel->click_count . ' clicks' : 'hot-picked' }}</small>
                            </div>
                        </a>
                    @empty
                        <div class="suggestion-card">
                            <div>
                                <strong>No hotel data yet</strong>
                                <small>Add hotels in the admin panel to unlock AI suggestions.</small>
                            </div>
                        </div>
                    @endforelse
                </div>
            </aside>
        </div>
    </section>

    {{-- Lower section: state filters and responsive hotel cards. --}}
    <section class="section-card">
        <div class="filter-bar" role="tablist" aria-label="Hotel state filters">
            @foreach ($stateFilters as $state)
                @php
                    $filterState = $state === 'All' ? 'All' : $state;
                    $filterParams = array_filter([
                        'state' => $filterState !== 'All' ? $filterState : null,
                    ]);
                @endphp

                <a
                    href="{{ route('home', $filterParams) }}"
                    class="filter-chip {{ $activeState === $filterState ? 'active' : '' }}"
                >
                    {{ $filterState }}
                </a>
            @endforeach
        </div>

        @if (($searchTerm ?? '') !== '')
            <p style="margin: 0 0 16px; color: #5a4a3f; font-weight: 600;">
                Showing results for "{{ $searchTerm }}" by hotel name or state.
            </p>
        @endif

        <div class="hotel-grid">
            @forelse ($filteredHotels as $hotel)
                <article class="hotel-card">
                    <img class="hotel-image" src="{{ $buildHotelImage($hotel) }}" alt="{{ $hotel->name }}">

                    <div class="hotel-details">
                        <div class="hotel-meta">
                            <h3>{{ $hotel->name }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($hotel->description ?? 'No description available yet.', 120) }}</p>
                        </div>

                        <div class="hotel-footer">
                            <a class="book-button" href="{{ route('user.booking.create', $hotel->id) }}">Book</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    No hotels match this keyword for hotel name or state. Try another keyword or change the state filter.
                </div>
            @endforelse
        </div>
    </section>
</div>

@push('scripts')
<script>
    // The homepage uses a GET form so search and filter state remain shareable in the URL.
</script>
@endpush
@endsection
