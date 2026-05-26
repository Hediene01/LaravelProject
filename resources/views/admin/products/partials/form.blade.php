@php
    $isEditing = $product->exists;
@endphp

<div class="admin-form-grid">
    <label>
        Category
        <select name="category_id" required>
            <option value="">Select a category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        SKU
        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required>
    </label>

    <label>
        Product name
        <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
    </label>

    <label>
        Keywords
        <input type="text" name="keywords" value="{{ old('keywords', $product->keywords) }}" placeholder="audio, wireless, premium">
    </label>

    <label>
        Price
        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" required>
    </label>

    <label>
        Compare price
        <input type="number" step="0.01" min="0" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}">
    </label>

    <label>
        Stock
        <input type="number" min="0" name="inventory" value="{{ old('inventory', $product->inventory ?? 0) }}" required>
    </label>

    <label>
        Minimum stock
        <input type="number" min="0" name="min_stock" value="{{ old('min_stock', $product->min_stock ?? 0) }}" required>
    </label>

    <label>
        Discount %
        <input type="number" min="0" max="100" name="discount" value="{{ old('discount', $product->discount ?? 0) }}" required>
    </label>

    <label>
        Image URL
        <input type="text" name="image_url" value="{{ old('image_url', $product->image_url) }}" placeholder="https://... or /images/demo.png">
    </label>
</div>

<label>
    Short description
    <textarea name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
</label>

<label>
    Product detail
    <textarea name="detail" rows="6" placeholder="Longer content for the admin CRUD lesson">{{ old('detail', $product->detail) }}</textarea>
</label>

<label>
    Upload image
    <input type="file" name="image" accept="image/*">
</label>

@if ($isEditing && $product->image_url)
    <div class="admin-image-preview">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
    </div>
@endif

<div class="admin-checkbox-grid">
    <label class="checkbox-row">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>
        <span>Featured product</span>
    </label>

    <label class="checkbox-row">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $isEditing ? $product->is_active : true))>
        <span>Active status</span>
    </label>
</div>

<div class="hero-actions">
    <button type="submit" class="button button-dark">
        {{ $isEditing ? 'Update product' : 'Create product' }}
    </button>
    <a class="button button-light" href="{{ route('admin.product.index') }}">Cancel</a>
</div>
