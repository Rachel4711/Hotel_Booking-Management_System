@extends('Layouts.user')

@section('title', 'Payment')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endpush

@section('content')
<div class="payment-container">
    <h1 class="payment-header">Secure Payment</h1>
    <p class="payment-subtitle">Complete your booking for <strong>{{ $booking->room->hotel->name ?? 'Your Hotel' }}</strong></p>

    <div class="payment-layout">
        <div>
            <form id="paymentForm" method="POST" action="{{ route('user.payment.process', $booking->id) }}">
                @csrf
                <div class="payment-methods-wrapper">
                    <h2 class="payment-methods-title">Select Payment Method</h2>

                    <label class="payment-method-item">
                        <input type="radio" name="payment_method" value="card" required class="payment-radio" onchange="updatePaymentMsg()">
                        <div class="payment-text">
                            <span class="method-title">💳 Credit/Debit Card</span>
                            <span class="method-desc">Visa, Mastercard, or other major cards</span>
                        </div>
                    </label>

                    <label class="payment-method-item">
                        <input type="radio" name="payment_method" value="online_banking" required class="payment-radio" onchange="updatePaymentMsg()">
                        <div class="payment-text">
                            <span class="method-title">🏦 Online Banking (FPX)</span>
                            <span class="method-desc">Transfer directly from your bank account</span>
                        </div>
                    </label>

                    <label class="payment-method-item">
                        <input type="radio" name="payment_method" value="e-wallet" required class="payment-radio" onchange="updatePaymentMsg()">
                        <div class="payment-text">
                            <span class="method-title">📱 E-Wallet</span>
                            <span class="method-desc">Touch 'n Go, GrabPay, Boost</span>
                        </div>
                    </label>
                </div>

                <div id="paymentInfo" class="payment-info-box">
                    Please select a payment method above.
                </div>

                <label class="terms-section">
                    <input type="checkbox" id="agreeTerms" required class="terms-checkbox">
                    <span class="terms-label">I agree to the <strong>booking terms and conditions</strong> and understand the cancellation policy.</span>
                </label>

                <div class="action-buttons">
                    <a href="javascript:history.back()" class="btn-back">← Back</a>
                    <button type="submit" class="btn-submit">🔒 Pay Now</button>
                </div>
            </form>
        </div>

        <div>
            <div class="order-summary-card sticky-element">
                <h3 class="summary-title">Order Summary</h3>
                <div class="ref-box">Ref: {{ $booking->booking_code ?? 'PENDING' }}</div>
                
                <div class="summary-details">
                    <div class="detail-row">
                        <span>Check-in:</span>
                        <strong>{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</strong>
                    </div>
                    <div class="detail-row">
                        <span>Check-out:</span>
                        <strong>{{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</strong>
                    </div>
                    <div class="detail-row pb-border">
                        <span>Guests:</span>
                        <strong>{{ $booking->guest_count }}</strong>
                    </div>

                    @php
                        $subtotal = $totalOrderPrice;
                        $tax = $subtotal * 0.06;
                        $total = $subtotal + $tax;
                    @endphp

                    <div class="detail-row mt-3">
                        <span>Subtotal</span>
                        <span>RM {{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="detail-row pb-border">
                        <span>Tax (6%)</span>
                        <span>RM {{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="total-row mt-3">
                        <span>Total Amount</span>
                        <span class="total-price">RM {{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updatePaymentMsg() {
    const method = document.querySelector('input[name="payment_method"]:checked').value;
    const infoDiv = document.getElementById('paymentInfo');
    const msgs = {
        'card': '✅ You will be redirected to our secure gateway to enter card details.',
        'online_banking': '🏦 You will be redirected to your bank\'s secure portal.',
        'e-wallet': '📱 You will be redirected to your e-wallet provider app.'
    };
    infoDiv.innerHTML = `<strong>Note:</strong> ${msgs[method]}`;
}
</script>
@endpush
@endsection