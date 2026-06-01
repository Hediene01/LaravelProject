@extends('layouts.admin')

@section('title', $order->order_number.' | Admin Panel')

@section('content')
    <section class="section-heading">
        <div>
            <span class="eyebrow">Order detail</span>
            <h1>{{ $order->order_number }}</h1>
        </div>
        <a class="button button-light" href="{{ route('admin.orders.index') }}">Back to orders</a>
    </section>

    <section class="admin-show-grid">
        <div class="admin-panel-card">
            <h2>Customer</h2>
            <p><strong>{{ $order->customer_name }}</strong></p>
            <p>{{ $order->customer_email }}</p>
            <p>{{ $order->customer_phone }}</p>
            <p>{{ $order->shipping_address }}, {{ $order->city }}, {{ $order->postal_code }}</p>

            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="admin-form-card-inner">
                @csrf
                @method('PATCH')
                <label>
                    Status
                    <select name="status">
                        @foreach (['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </label>
                <button type="submit" class="button button-dark">Update status</button>
            </form>
        </div>

        <div class="admin-panel-card">
            <h2>Items</h2>
            @foreach ($order->orderItems as $item)
                <div class="summary-item">
                    <div>
                        <strong>{{ $item->product_name }}</strong>
                        <span>{{ $item->quantity }} x ${{ number_format((float) $item->price, 2) }}</span>
                    </div>
                    <strong>${{ number_format((float) $item->line_total, 2) }}</strong>
                </div>
            @endforeach

            <div class="summary-row total">
                <span>Total</span>
                <strong>${{ number_format((float) $order->total_amount, 2) }}</strong>
            </div>
        </div>
    </section>
@endsection
