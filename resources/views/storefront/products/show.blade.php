@extends('layouts.app')

@section('title', $product->name.' | Luna Commerce')

@section('content')
    <nav class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}">Products</a>
        <span>/</span>
        <span>{{ $product->name }}</span>
    </nav>

    <section class="product-detail">
        <div class="product-detail-image-wrap">
            <img class="product-detail-image" src="{{ $product->image_url }}" alt="{{ $product->name }}">
        </div>

        <div class="product-detail-copy">
            <span class="eyebrow">{{ $product->category->name }}</span>
            <h1>{{ $product->name }}</h1>
            <p class="lead">{{ $product->description }}</p>

            <div class="price-row detail-price">
                <strong>${{ number_format((float) $product->price, 2) }}</strong>
                @if ($product->compare_price)
                    <span>${{ number_format((float) $product->compare_price, 2) }}</span>
                @endif
            </div>

            <div class="info-row">
                <span>SKU: {{ $product->sku }}</span>
                <span>{{ $product->inventory }} in stock</span>
            </div>

            <form action="{{ route('cart.store') }}" method="POST" class="detail-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <label>
                    Quantity
                    <input type="number" min="1" max="{{ $product->inventory }}" name="quantity" value="1">
                </label>

                <button type="submit" class="button button-dark">Add to cart</button>
            </form>

            @if (! empty($product->attributes))
                <div class="spec-card">
                    <h2>Product details</h2>
                    <div class="spec-grid">
                        @foreach ($product->attributes as $label => $value)
                            <div>
                                <strong>{{ $label }}</strong>
                                <span>{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="section-heading">
            <div>
                <span class="eyebrow">You may also like</span>
                <h2>More from {{ $product->category->name }}</h2>
            </div>
        </section>

        <section class="product-grid compact-grid">
            @foreach ($relatedProducts as $product)
                @include('storefront.partials.product-card', ['product' => $product])
            @endforeach
        </section>
    @endif
@endsection
