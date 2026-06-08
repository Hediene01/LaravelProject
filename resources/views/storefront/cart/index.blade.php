@extends('layouts.app')

@section('title', 'My Cart | Luna Commerce')

@section('content')
    <section class="page-heading">
        <div>
            <h1>My Cart</h1>
        </div>
    </section>

    @if ($cartItems->isEmpty())
        <div class="empty-state">
            <h2>Your cart is empty</h2>
            <p>Add products from the catalog to get started.</p>
            <a class="button button-dark" href="{{ route('products.index') }}">Browse products</a>
        </div>
    @else
        <section class="cart-layout">
            <div class="cart-items">
                @foreach ($cartItems as $item)
                    @php $lineTotal = $item->price * $item->quantity; @endphp
                    <article class="cart-item">
                        @if ($item->product && $item->product->image_url)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                        @endif

                        <div class="cart-item-copy">
                            <h2>{{ $item->product->name ?? 'Product Deleted' }}</h2>
                            <strong>${{ number_format($item->price, 2) }}</strong>
                        </div>

                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="cart-form">
                            @csrf
                            <label>
                                Quantity
                                <input type="number" name="quantity" min="1" value="{{ $item->quantity }}">
                            </label>
                            <button type="submit" class="button button-light">Update</button>
                        </form>

                        <div class="cart-item-actions">
                            <strong>${{ number_format($lineTotal, 2) }}</strong>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
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
                @php $subtotal = $cartItems->sum(fn ($i) => $i->price * $i->quantity); @endphp
                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong>${{ number_format($subtotal, 2) }}</strong>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <strong>${{ number_format($subtotal, 2) }}</strong>
                </div>
                <a class="button button-dark button-block" href="{{ route('checkout') }}">Continue to checkout</a>
            </aside>
        </section>
    @endif
@endsection
