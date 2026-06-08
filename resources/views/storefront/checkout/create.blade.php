@extends('layouts.app')

@section('title', 'Checkout | Luna Commerce')

@section('content')
    <section class="page-heading">
        <div>
            <h1>Checkout</h1>
        </div>
    </section>

    <section class="checkout-layout">
        <form action="{{ route('place.order') }}" method="POST" class="checkout-form">
            @csrf

            <h3>Billing Details</h3>

            <div class="field-grid">
                <label>
                    Full Name
                    <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" required>
                    @error('name')<small>{{ $message }}</small>@enderror
                </label>

                <label>
                    Email
                    <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required>
                    @error('email')<small>{{ $message }}</small>@enderror
                </label>

                <label>
                    Phone
                    <input type="text" name="phone" value="{{ old('phone') }}">
                    @error('phone')<small>{{ $message }}</small>@enderror
                </label>

                <label>
                    City
                    <input type="text" name="city" value="{{ old('city') }}">
                    @error('city')<small>{{ $message }}</small>@enderror
                </label>

                <label>
                    Country
                    <input type="text" name="country" value="{{ old('country') }}">
                </label>

                <label>
                    ZIP Code
                    <input type="text" name="zip_code" value="{{ old('zip_code') }}">
                </label>
            </div>

            <label>
                Address
                <input type="text" name="address" value="{{ old('address') }}" required>
                @error('address')<small>{{ $message }}</small>@enderror
            </label>

            <h3>Shipping Method</h3>
            <label>
                <input type="radio" name="shipping_method" value="Free Shipping" checked> Free Shipping — $0.00
            </label>
            <label>
                <input type="radio" name="shipping_method" value="Standard Shipping"> Standard Shipping — $4.00
            </label>

            <h3>Payment Method</h3>
            <label>
                <input type="radio" name="payment_method" value="Direct Bank Transfer" checked> Direct Bank Transfer
            </label>
            <label>
                <input type="radio" name="payment_method" value="Cash on Delivery"> Cash on Delivery
            </label>
            <label>
                <input type="radio" name="payment_method" value="Paypal"> Paypal
            </label>

            <button type="submit" class="button button-dark">Place Order</button>
        </form>

        <aside class="summary-card">
            <h2>Order Review</h2>

            @foreach ($cartItems as $item)
                <div class="summary-item">
                    <div>
                        <strong>{{ $item->product->name ?? 'Product Deleted' }}</strong>
                        <span>{{ $item->quantity }} x ${{ number_format($item->price, 2) }}</span>
                    </div>
                    <strong>${{ number_format($item->price * $item->quantity, 2) }}</strong>
                </div>
            @endforeach

            <div class="summary-row">
                <span>Subtotal</span>
                <strong>${{ number_format($subtotal, 2) }}</strong>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <strong>{{ $shippingPrice > 0 ? '$'.number_format($shippingPrice, 2) : 'Free' }}</strong>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <strong>${{ number_format($total, 2) }}</strong>
            </div>
        </aside>
    </section>
@endsection
