@extends('layouts.app')

@section('title', 'Payment Complete | ACES Lacrosse Training')

@section('content')
@include('pages.customer_sessions._styles')

@php
    $paymentLog = $order->paymentLogs->first();
    $transactionId = $paymentLog ? $paymentLog->transaction_id : null;
@endphp

<div class="ao-training">
    <div class="ao-wrap" style="max-width: 850px;">
        @include('pages.customer_sessions._toolbar')

        @if(session('success'))
            <div class="ao-alert ao-alert-success mb-3">{{ session('success') }}</div>
        @endif

        <div class="ao-panel p-4 p-md-5 text-center">
            <div class="payment-success-icon">
                <i class="fa-solid fa-check"></i>
            </div>

            <div class="ao-eyebrow">PAYMENT COMPLETE</div>
            <h1 class="ao-section-title mt-2">Your Training order is confirmed</h1>
            <p class="ao-muted mt-2 mb-0">
                Your receipt and any confirmed booking emails have been sent to your Training account email.
            </p>

            <div class="payment-success-summary mt-4 text-start">
                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                    <div>
                        <small class="ao-muted d-block">ORDER</small>
                        <div class="payment-success-order-no">{{ $order->order_no }}</div>
                    </div>

                    <div class="text-end">
                        <small class="ao-muted d-block">TOTAL PAID</small>
                        <div class="payment-success-total">${{ number_format((float) $order->total, 2) }}</div>
                    </div>
                </div>

                <div class="payment-success-items mt-4">
                    @foreach($order->items as $item)
                        <div class="ao-summary-row payment-success-item-row">
                            <span>
                                {{ $item->package_name }} × {{ $item->quantity }}
                            </span>
                            <strong>${{ number_format((float) $item->total_price, 2) }}</strong>
                        </div>
                    @endforeach
                </div>

                @if(!empty($transactionId))
                    <div class="payment-success-transaction mt-3">
                        <i class="fa-regular fa-credit-card"></i>
                        <span>Transaction: {{ $transactionId }}</span>
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-center gap-2 flex-wrap mt-4">
                <a class="ao-btn ao-btn-red" href="{{ route('em.customer.dashboard') }}">
                    <i class="fa-solid fa-table-cells-large"></i>
                    View Training Dashboard
                </a>

                <a class="ao-btn ao-btn-outline" href="{{ route('em.customer.index') }}">
                    <i class="fa-regular fa-calendar"></i>
                    Continue Training
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .payment-success-icon {
        width: 78px;
        height: 78px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        background: #ecfdf3;
        color: #067647;
        font-size: 34px;
        box-shadow: 0 12px 28px rgba(6, 118, 71, .12);
    }

    .payment-success-summary {
        border: 1px solid rgba(97, 30, 178, .10);
        border-radius: 20px;
        background: #faf9fc;
        padding: 22px;
    }

    .payment-success-order-no {
        margin-top: 3px;
        font-size: 20px;
        font-weight: 900;
        color: #171021;
        word-break: break-word;
    }

    .payment-success-total {
        margin-top: 3px;
        color: #611eb2;
        font-size: 30px;
        line-height: 1;
        font-weight: 900;
    }

    .payment-success-items {
        border-top: 1px solid rgba(97, 30, 178, .10);
        border-bottom: 1px solid rgba(97, 30, 178, .10);
        padding: 10px 0;
    }

    .payment-success-item-row {
        margin: 0;
        padding: 9px 0;
    }

    .payment-success-transaction {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #667085;
        font-size: 13px;
        font-weight: 800;
    }

    .payment-success-transaction i {
        color: #611eb2;
    }
</style>
@endsection
