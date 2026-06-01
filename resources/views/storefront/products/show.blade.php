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

            @if (! empty($product->gallery))
                <div class="gallery-grid">
                    @foreach ($product->gallery as $galleryImage)
                        <img src="{{ $galleryImage }}" alt="{{ $product->name }}">
                    @endforeach
                </div>
            @endif
        </div>

        <div class="product-detail-copy">
            <span class="eyebrow">{{ $product->category->name }}</span>
            <h1>{{ $product->name }}</h1>
            <p class="lead">{{ $product->description }}</p>

            <div class="info-row">
                @if ($product->brand)
                    <span>Brand: {{ $product->brand->name }}</span>
                @endif
                <span>Rating: {{ number_format((float) $product->average_rating, 1) }}/5</span>
                <span>{{ $product->reviews_count }} approved reviews</span>
            </div>

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

            @auth
                <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="inline-cart-form">
                    @csrf
                    <button type="submit" class="button button-light">Toggle wishlist</button>
                </form>
            @endauth

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

    <section class="section-heading">
        <div>
            <span class="eyebrow">Customer feedback</span>
            <h2>Reviews</h2>
        </div>
    </section>

    <section class="review-layout">
        <div class="summary-card">
            <div class="summary-row total">
                <span>Average rating</span>
                <strong>{{ number_format((float) $product->average_rating, 1) }}/5</strong>
            </div>
            <div class="summary-row">
                <span>Approved reviews</span>
                <strong>{{ $product->reviews_count }}</strong>
            </div>

            @auth
                @if ($canReview)
                    <form action="{{ route('reviews.store', $product) }}" method="POST" class="checkout-form">
                        @csrf

                        <label>
                            Rating
                            <select name="rating" required>
                                <option value="">Choose a rating</option>
                                @for ($rating = 5; $rating >= 1; $rating--)
                                    <option value="{{ $rating }}">{{ $rating }} / 5</option>
                                @endfor
                            </select>
                        </label>

                        <label>
                            Title
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Quick summary">
                        </label>

                        <label>
                            Review
                            <textarea name="body" rows="5" required>{{ old('body') }}</textarea>
                        </label>

                        <button type="submit" class="button button-dark">Submit review</button>
                    </form>
                @else
                    <p class="account-note">You can submit a review after purchasing this product.</p>
                @endif
            @else
                <p class="account-note">Log in and place an order to submit a review.</p>
            @endauth
        </div>

        <div class="review-list">
            @forelse ($product->approvedReviews as $review)
                <article class="review-card">
                    <div class="review-head">
                        <strong>{{ $review->title ?: 'Verified review' }}</strong>
                        <span>{{ $review->rating }}/5</span>
                    </div>
                    <p>{{ $review->body }}</p>
                    <small>{{ $review->user->name }} · {{ $review->created_at->format('M d, Y') }}</small>
                </article>
            @empty
                <div class="empty-inline">No approved reviews yet.</div>
            @endforelse
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
            @foreach ($relatedProducts as $relatedProduct)
                @include('storefront.partials.product-card', ['product' => $relatedProduct])
            @endforeach
        </section>
    @endif
@endsection
