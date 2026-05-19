@extends('layouts.app')

@section('title', 'Your Cart | Luna Commerce')

@section('content')
    <section class="page-heading">
        <div>
            <span class="eyebrow">Session cart</span>
            <h1>Your shopping cart</h1>
            <p>Review line items, update quantities, and continue to checkout.</p>
        </div>
    </section>

    @if ($items->isEmpty())
        <div class="empty-state">
            <h2>Your cart is empty</h2>
            <p>Add a few products from the catalog to test the checkout flow.</p>
            <a class="button button-dark" href="{{ route('products.index') }}">Browse products</a>
        </div>
    @else
        <section class="cart-layout">
            <div class="cart-items">
                @foreach ($items as $item)
                    <article class="cart-item">
                        <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}">

                        <div class="cart-item-copy">
                            <h2><a href="{{ route('products.show', $item['slug']) }}">{{ $item['name'] }}</a></h2>
                            <p>SKU: {{ $item['sku'] }}</p>
                            <strong>${{ number_format((float) $item['price'], 2) }}</strong>
                        </div>

                        <form action="{{ route('cart.update', $item['slug']) }}" method="POST" class="cart-form">
                            @csrf
                            @method('PATCH')
                            <label>
                                Quantity
                                <input type="number" name="quantity" min="0" value="{{ $item['quantity'] }}">
                            </label>
                            <button type="submit" class="button button-light">Update</button>
                        </form>

                        <div class="cart-item-actions">
                            <strong>${{ number_format((float) $item['line_total'], 2) }}</strong>
                            <form action="{{ route('cart.destroy', $item['slug']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-button">Remove</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <aside class="summary-card">
                <h2>Order summary</h2>
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
                <a class="button button-dark button-block" href="{{ route('checkout.create') }}">Continue to checkout</a>
            </aside>
        </section>
    @endif
@endsection
