@extends('layouts.app')

@section('title', 'Order Confirmed | Luna Commerce')

@section('content')
    <section class="success-card">
        <span class="eyebrow">Order confirmed</span>
        <h1>Thanks, {{ $order->customer_name }}.</h1>
        <p>Your order <strong>{{ $order->order_number }}</strong> has been stored successfully in the database.</p>

        <div class="confirmation-grid">
            <div>
                <span>Status</span>
                <strong>{{ ucfirst($order->status) }}</strong>
            </div>
            <div>
                <span>Email</span>
                <strong>{{ $order->customer_email }}</strong>
            </div>
            <div>
                <span>Total</span>
                <strong>${{ number_format((float) $order->total_amount, 2) }}</strong>
            </div>
        </div>

        <div class="summary-card order-items-card">
            <h2>Items ordered</h2>
            @foreach ($order->orderItems as $item)
                <div class="summary-item">
                    <div>
                        <strong>{{ $item->product_name }}</strong>
                        <span>{{ $item->quantity }} x ${{ number_format((float) $item->price, 2) }}</span>
                    </div>
                    <strong>${{ number_format((float) $item->line_total, 2) }}</strong>
                </div>
            @endforeach
        </div>

        <div class="hero-actions">
            <a class="button button-dark" href="{{ route('products.index') }}">Continue shopping</a>
            <a class="button button-light" href="{{ route('home') }}">Back to homepage</a>
        </div>
    </section>
@endsection
