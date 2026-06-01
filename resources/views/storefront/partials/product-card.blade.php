<article class="product-card">
    <a class="product-image-link" href="{{ route('products.show', $product) }}">
        <img class="product-image" src="{{ $product->image_url }}" alt="{{ $product->name }}">
        @if ($product->discount > 0)
            <span class="image-badge">{{ $product->discount }}% off</span>
        @endif
    </a>

    <div class="product-body">
        <div class="product-meta">
            <span class="chip">{{ $product->category->name }}</span>
            @if ($product->brand)
                <span class="chip">{{ $product->brand->name }}</span>
            @endif
            @if ($product->is_featured)
                <span class="chip chip-accent">Featured</span>
            @endif
        </div>

        <h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3>
        <p>{{ $product->description }}</p>

        @if (($product->reviews_count ?? 0) > 0)
            <div class="rating-row">
                <strong>{{ number_format((float) $product->average_rating, 1) }}/5</strong>
                <span>{{ $product->reviews_count }} reviews</span>
            </div>
        @endif

        <div class="price-row">
            <strong>${{ number_format((float) $product->price, 2) }}</strong>
            @if ($product->compare_price)
                <span>${{ number_format((float) $product->compare_price, 2) }}</span>
            @endif
        </div>

        <form action="{{ route('cart.store') }}" method="POST" class="inline-cart-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="button button-dark">Add to cart</button>
        </form>
    </div>
</article>
