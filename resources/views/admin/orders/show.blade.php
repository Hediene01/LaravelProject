@extends('layouts.admin')

@section('title', 'Order #'.$order->order_number.' | Admin Panel')

@section('content')
    <section class="section-heading">
        <div>
            <span class="eyebrow">Order detail</span>
            <h1>Order #{{ $order->order_number }}</h1>
        </div>
        <a class="button button-light" href="{{ route('admin.orders.index') }}">Back to orders</a>
    </section>

    <section class="admin-show-grid">
        <div class="admin-panel-card">
            <h2>Customer Information</h2>
            <table class="admin-table">
                <tr><th>Name</th><td>{{ $order->customer_name }}</td></tr>
                <tr><th>Email</th><td>{{ $order->customer_email }}</td></tr>
                <tr><th>Phone</th><td>{{ $order->customer_phone }}</td></tr>
                <tr><th>Address</th><td>{{ $order->shipping_address }}</td></tr>
                <tr><th>City</th><td>{{ $order->city }}</td></tr>
                <tr><th>Country</th><td>{{ $order->country }}</td></tr>
                <tr><th>ZIP Code</th><td>{{ $order->postal_code }}</td></tr>
            </table>
        </div>

        <div class="admin-panel-card">
            <h2>Order Information</h2>
            <table class="admin-table">
                <tr><th>Order ID</th><td>{{ $order->order_number }}</td></tr>
                <tr><th>Status</th><td>{{ $order->status }}</td></tr>
                <tr><th>Payment Method</th><td>{{ $order->payment_method }}</td></tr>
                <tr><th>Shipping Method</th><td>{{ $order->shipping_method }}</td></tr>
                <tr><th>Date</th><td>{{ $order->created_at->format('m/d/Y H:i') }}</td></tr>
                <tr><th>Subtotal</th><td>${{ number_format((float) $order->subtotal, 2) }}</td></tr>
                <tr><th>Shipping</th><td>${{ number_format((float) $order->shipping_amount, 2) }}</td></tr>
                <tr><th>Total</th><td><strong>${{ number_format((float) $order->total_amount, 2) }}</strong></td></tr>
            </table>

            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="admin-form-card-inner" style="margin-top:1rem;">
                @csrf
                <label>
                    Change Status
                    <select name="status" class="input-field">
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" @selected($order->status === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </label>
                <button type="submit" class="button button-dark">Update Status</button>
            </form>
        </div>
    </section>

    <div class="admin-panel-card" style="margin-top:1.5rem;">
        <h2>Order Items</h2>
        @foreach ($order->items as $item)
            <div class="summary-item">
                <div>
                    <strong>{{ $item->product_name }}</strong>
                    <span>{{ $item->quantity }} x ${{ number_format((float) $item->price, 2) }}</span>
                </div>
                <strong>${{ number_format((float) $item->line_total, 2) }}</strong>
            </div>
        @endforeach

        <div class="summary-row">
            <span>Subtotal</span>
            <strong>${{ number_format((float) $order->subtotal, 2) }}</strong>
        </div>
        <div class="summary-row">
            <span>Shipping</span>
            <strong>${{ number_format((float) $order->shipping_amount, 2) }}</strong>
        </div>
        <div class="summary-row total">
            <span>Total</span>
            <strong>${{ number_format((float) $order->total_amount, 2) }}</strong>
        </div>
    </div>
@endsection
