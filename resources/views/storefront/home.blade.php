@extends('layouts.app')

@section('title', 'Luna Commerce | Modern Laravel Storefront')

@section('content')
    <section class="hero">
        <div class="hero-copy">
            <span class="eyebrow">Laravel + PHP Ecommerce</span>
            <h1>Sell a curated tech catalog with a fast storefront, wishlist, reviews, API, and admin workflows.</h1>
            <p>
                Luna Commerce now combines a public store, customer account tools, richer catalog metadata,
                and protected admin management in one upgraded Laravel project.
            </p>

            <div class="hero-actions">
                <a class="button button-dark" href="{{ route('products.index') }}">Shop the catalog</a>
                <a class="button button-light" href="{{ route('checkout.create') }}">Go to checkout</a>
            </div>

            <div class="stat-grid">
                <div class="stat-card">
                    <strong>{{ $featuredProducts->count() }}</strong>
                    <span>Featured products</span>
                </div>
                <div class="stat-card">
                    <strong>{{ $categories->count() }}</strong>
                    <span>Catalog categories</span>
                </div>
                <div class="stat-card">
                    <strong>{{ $brands->count() }}</strong>
                    <span>Curated brands</span>
                </div>
            </div>
        </div>

        <div class="hero-panel">
            <div class="hero-panel-card">
                <span class="eyebrow">Commerce toolkit</span>
                <h2>What's included</h2>
                <ul class="feature-list">
                    <li>Catalog browsing with category, brand, and search filters</li>
                    <li>Wishlist and customer review flows for signed-in users</li>
                    <li>Session cart and checkout with stored orders</li>
                    <li>Admin CRUD, moderation, and public JSON API endpoints</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="section-heading">
        <div>
            <span class="eyebrow">Trusted makers</span>
            <h2>Featured brands</h2>
        </div>
    </section>

    <section class="brand-grid">
        @foreach ($brands as $brand)
            <a class="brand-card" href="{{ route('products.index', ['brand' => $brand->slug]) }}">
                @if ($brand->logo_url)
                    <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}">
                @endif
                <strong>{{ $brand->name }}</strong>
                <span>{{ $brand->products_count }} products</span>
            </a>
        @endforeach
    </section>

    <section class="section-heading">
        <div>
            <span class="eyebrow">Browse by category</span>
            <h2>Shop by collection</h2>
        </div>
    </section>

    <section class="category-grid">
        @foreach ($categories as $category)
            <a class="category-card" href="{{ route('products.index', ['category' => $category->slug]) }}">
                @if ($category->image_url)
                    <div class="category-card-media">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}">
                    </div>
                @endif

                <div class="category-card-copy">
                    <strong>{{ $category->name }}</strong>
                    <p>{{ $category->description }}</p>
                    <span>{{ $category->products_count }} products</span>
                </div>
            </a>
        @endforeach
    </section>

    <section class="section-heading">
        <div>
            <span class="eyebrow">Featured picks</span>
            <h2>Top products to launch with</h2>
        </div>
        <a class="text-link" href="{{ route('products.index') }}">View all products</a>
    </section>

    <section class="product-grid">
        @foreach ($featuredProducts as $product)
            @include('storefront.partials.product-card', ['product' => $product])
        @endforeach
    </section>

    <section class="section-heading">
        <div>
            <span class="eyebrow">New arrivals</span>
            <h2>Fresh catalog entries</h2>
        </div>
    </section>

    <section class="product-grid compact-grid">
        @foreach ($newArrivals as $product)
            @include('storefront.partials.product-card', ['product' => $product])
        @endforeach
    </section>
@endsection
