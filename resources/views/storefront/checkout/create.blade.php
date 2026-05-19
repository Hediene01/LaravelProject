@extends('layouts.app')

@section('title', 'Checkout | Luna Commerce')

@section('content')
    <section class="page-heading">
        <div>
            <span class="eyebrow">Checkout</span>
            <h1>Complete your order</h1>
            <p>Customer information is stored in the database with order items for each purchase.</p>
        </div>
    </section>

    <section class="checkout-layout">
        <form action="{{ route('checkout.store') }}" method="POST" class="checkout-form">
            @csrf

            <div class="field-grid">
                <label>
                    Full name
                    <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" required>
                    @error('customer_name')<small>{{ $message }}</small>@enderror
                </label>

                <label>
                    Email
                    <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" required>
                    @error('customer_email')<small>{{ $message }}</small>@enderror
                </label>

                <label>
                    Phone
                    <input type="text" name="customer_phone" value="{{ old('customer_phone', auth()->user()?->phone) }}" required>
                    @error('customer_phone')<small>{{ $message }}</small>@enderror
                </label>

                <label>
                    City
                    <input type="text" name="city" value="{{ old('city', auth()->user()?->city) }}" required>
                    @error('city')<small>{{ $message }}</small>@enderror
                </label>
            </div>

            <label>
                Shipping address
                <input type="text" name="shipping_address" value="{{ old('shipping_address', auth()->user()?->address_line) }}" required>
                @error('shipping_address')<small>{{ $message }}</small>@enderror
            </label>

            <label>
                Postal code
                <input type="text" name="postal_code" value="{{ old('postal_code', auth()->user()?->postal_code) }}" required>
                @error('postal_code')<small>{{ $message }}</small>@enderror
            </label>

            <label>
                Notes
                <textarea name="notes" rows="5" placeholder="Delivery instructions or internal order notes">{{ old('notes') }}</textarea>
                @error('notes')<small>{{ $message }}</small>@enderror
            </label>

            <button type="submit" class="button button-dark">Place order</button>
        </form>

        <aside class="summary-card">
            <h2>Order summary</h2>

            @foreach ($items as $item)
                <div class="summary-item">
                    <div>
                        <strong>{{ $item['name'] }}</strong>
                        <span>{{ $item['quantity'] }} x ${{ number_format((float) $item['price'], 2) }}</span>
                    </div>
                    <strong>${{ number_format((float) $item['line_total'], 2) }}</strong>
                </div>
            @endforeach

            <div class="summary-row">
                <span>Subtotal</span>
                <strong>${{ number_format($subtotal, 2) }}</strong>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <strong>{{ $shippingAmount > 0 ? '$'.number_format($shippingAmount, 2) : 'Free' }}</strong>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <strong>${{ number_format($total, 2) }}</strong>
            </div>
        </aside>
    </section>
@endsection
