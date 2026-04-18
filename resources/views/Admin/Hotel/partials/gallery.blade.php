@php
    $modalId = $modalId ?? 'hotel-gallery-modal';
    $visibleImages = collect($galleryImages ?? [])->take(4)->values();
    $hiddenImageCount = max(0, collect($galleryImages ?? [])->count() - 4);
@endphp

@if (collect($galleryImages ?? [])->isNotEmpty())
    <div class="hotel-gallery">
        @foreach ($visibleImages as $imageUrl)
            <div class="hotel-gallery-item">
                <img src="{{ $imageUrl }}" alt="Hotel image {{ $loop->iteration }}">

                @if ($loop->last && $hiddenImageCount > 0)
                    <button type="button" class="hotel-gallery-overlay" data-open-gallery="{{ $modalId }}">
                        +{{ $hiddenImageCount }}
                    </button>
                @endif
            </div>
        @endforeach
    </div>

    <div class="hotel-modal" id="{{ $modalId }}" aria-hidden="true">
        <div class="hotel-modal-card hotel-gallery-modal-card" role="dialog" aria-modal="true" aria-labelledby="{{ $modalId }}-title">
            <div class="hotel-gallery-modal-header">
                <h3 id="{{ $modalId }}-title">Hotel Image Gallery</h3>
                <button type="button" class="hotel-modal-close" data-close-gallery="{{ $modalId }}">
                    <i class="uil uil-times"></i>
                </button>
            </div>

            <div class="hotel-gallery-grid">
                @foreach ($galleryImages as $imageUrl)
                    <div class="hotel-gallery-grid-item">
                        <img src="{{ $imageUrl }}" alt="Gallery image {{ $loop->iteration }}">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('{{ $modalId }}');
        var openButtons = document.querySelectorAll('[data-open-gallery="{{ $modalId }}"]');
        var closeButtons = document.querySelectorAll('[data-close-gallery="{{ $modalId }}"]');

        function openModal() {
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
        }

        function closeModal() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
        }

        openButtons.forEach(function (button) {
            button.addEventListener('click', openModal);
        });

        closeButtons.forEach(function (button) {
            button.addEventListener('click', closeModal);
        });

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });
    });
    </script>
@endif
