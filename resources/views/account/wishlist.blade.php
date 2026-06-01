@extends('layouts.app')

@section('title', 'Wishlist | Luna Commerce')

@section('content')
    <section class="page-heading">
        <div>
            <span class="eyebrow">Saved for later</span>
            <h1>Your wishlist</h1>
            <p>Keep track of products you like and return to them from your account.</p>
        </div>
    </section>

    <section class="product-grid">
        @forelse ($wishlistItems as $wishlistItem)
            @include('storefront.partials.product-card', ['product' => $wishlistItem->product])
        @empty
            <div class="empty-state">
                <h2>Your wishlist is empty</h2>
                <p>Save items from any product page to build your personal shortlist.</p>
                <a class="button button-dark" href="{{ route('products.index') }}">Browse products</a>
            </div>
        @endforelse
    </section>
@endsection
