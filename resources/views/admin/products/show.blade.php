@extends('layouts.admin')

@section('title', $product->name.' | Admin Panel')

@section('content')
    <section class="section-heading">
        <div>
            <span class="eyebrow">Product detail</span>
            <h1>{{ $product->name }}</h1>
        </div>
        <div class="hero-actions">
            <a class="button button-light" href="{{ route('admin.product.edit', $product) }}">Edit</a>
            <a class="button button-dark" href="{{ route('admin.product.index') }}">Back to list</a>
        </div>
    </section>

    <section class="admin-show-grid">
        <div class="admin-panel-card">
            @if ($product->image_url)
                <img class="admin-show-image" src="{{ $product->image_url }}" alt="{{ $product->name }}">
            @endif

            <div class="spec-grid">
                <div><strong>Category</strong><span>{{ $product->category?->name }}</span></div>
                <div><strong>Author</strong><span>{{ $product->user?->name ?? 'Seeder' }}</span></div>
                <div><strong>SKU</strong><span>{{ $product->sku }}</span></div>
                <div><strong>Price</strong><span>${{ number_format((float) $product->price, 2) }}</span></div>
                <div><strong>Compare price</strong><span>{{ $product->compare_price ? '$'.number_format((float) $product->compare_price, 2) : '-' }}</span></div>
                <div><strong>Discount</strong><span>{{ $product->discount }}%</span></div>
                <div><strong>Stock</strong><span>{{ $product->inventory }}</span></div>
                <div><strong>Min stock</strong><span>{{ $product->min_stock }}</span></div>
                <div><strong>Status</strong><span>{{ $product->is_active ? 'Active' : 'Hidden' }}</span></div>
            </div>
        </div>

        <div class="admin-panel-card">
            <h2>Description</h2>
            <p>{{ $product->description }}</p>

            <h2>Keywords</h2>
            <p>{{ $product->keywords ?: 'No keywords' }}</p>

            <h2>Detail</h2>
            <p>{{ $product->detail ?: 'No detailed content provided.' }}</p>
        </div>
    </section>
@endsection
