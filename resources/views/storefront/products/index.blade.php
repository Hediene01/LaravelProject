@extends('layouts.app')

@section('title', 'Shop Products | Luna Commerce')

@section('content')
    <section class="page-heading">
        <div>
            <span class="eyebrow">Catalog</span>
            <h1>Shop products</h1>
            <p>Filter by category, brand, and search terms across the expanded storefront catalog.</p>
        </div>
    </section>

    <form action="{{ route('products.index') }}" method="GET" class="filter-bar">
        <input type="search" name="search" value="{{ $search }}" placeholder="Search products">

        <select name="category">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->slug }}" @selected($selectedCategory === $category->slug)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select name="brand">
            <option value="">All brands</option>
            @foreach ($brands as $brand)
                <option value="{{ $brand->slug }}" @selected($selectedBrand === $brand->slug)>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="button button-dark">Apply filters</button>
    </form>

    <section class="product-grid">
        @forelse ($products as $product)
            @include('storefront.partials.product-card', ['product' => $product])
        @empty
            <div class="empty-state">
                <h2>No products matched your filters</h2>
                <p>Try clearing the search or switching to another category or brand.</p>
            </div>
        @endforelse
    </section>

    <div class="pagination-wrap">
        {{ $products->links() }}
    </div>
@endsection
