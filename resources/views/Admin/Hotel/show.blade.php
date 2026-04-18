@extends('Layouts.admin')
@section('content')

@php
    $extraImages = collect($hotel->images ?? [])->filter()->values();
@endphp

<div class="page-shell">
    <a href="{{ route('hotels.index') }}" class="hotel-back-link">
        <i class="uil uil-arrow-left"></i>
        Back to Hotel List
    </a>

    <div class="page-header">
        <div>
            <h1 class="page-title">Hotel Detail</h1>
            <p class="page-subtitle">Viewing {{ $hotel->name }}</p>
        </div>
        <a href="{{ route('hotels.edit', $hotel) }}" class="form-action secondary">Edit Hotel</a>
    </div>

    <section class="panel-section" style="margin-bottom:14px;">
        @include('Admin.Hotel.partials.gallery', [
            'galleryImages' => $galleryImages,
            'modalId' => 'hotel-detail-gallery-modal',
        ])
    </section>

    <section class="panel-section">
        <div class="hotel-form-grid">
            <div class="hotel-field-group">
                <label class="hotel-field-label">name</label>
                <input type="text" class="input" value="{{ $hotel->name }}" readonly>
            </div>

            <div class="hotel-field-group">
                <label class="hotel-field-label">state</label>
                <input type="text" class="input" value="{{ $hotel->state }}" readonly>
            </div>

            <div class="hotel-field-group hotel-span-2">
                <label class="hotel-field-label">description</label>
                <textarea class="input hotel-textarea" readonly>{{ $hotel->description }}</textarea>
            </div>

            <div class="hotel-field-group hotel-span-2">
                <label class="hotel-field-label">address</label>
                <textarea class="input hotel-textarea" readonly>{{ $hotel->address }}</textarea>
            </div>

            <div class="hotel-field-group hotel-span-2">
                <label class="hotel-field-label">cover_image</label>
                <input type="text" class="input" value="{{ $hotel->cover_image }}" readonly>
            </div>

            <div class="hotel-field-group hotel-span-2">
                <label class="hotel-field-label">images</label>
                <textarea class="input hotel-textarea" readonly>{{ $extraImages->isNotEmpty() ? $extraImages->implode("\n") : '-' }}</textarea>
            </div>
        </div>
    </section>
</div>

@endsection
