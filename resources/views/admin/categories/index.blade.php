@extends('layouts.admin')

@section('title', 'Manage Categories | Admin Panel')

@section('content')
    <section class="page-heading">
        <div>
            <span class="eyebrow">Catalog structure</span>
            <h1>Categories</h1>
            <p>Create parent and child categories for a deeper storefront hierarchy.</p>
        </div>
    </section>

    <section class="admin-split-grid">
        <div class="admin-form-card">
            <h2>Create category</h2>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="admin-form-card-inner">
                @csrf
                <label>
                    Parent category
                    <select name="parent_id">
                        <option value="">Top-level category</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    Name
                    <input type="text" name="name" required>
                </label>
                <label>
                    Description
                    <textarea name="description" rows="4"></textarea>
                </label>
                <label>
                    Image URL
                    <input type="text" name="image_url">
                </label>
                <button type="submit" class="button button-dark">Create category</button>
            </form>
        </div>

        <div class="admin-panel-card">
            <h2>Existing categories</h2>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->parent?->name ?? 'Top-level' }}</td>
                                <td>{{ $category->products_count }}</td>
                                <td class="admin-actions-cell">
                                    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="admin-inline-form">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="parent_id" value="{{ $category->parent_id }}">
                                        <input type="hidden" name="name" value="{{ $category->name }}">
                                        <input type="hidden" name="description" value="{{ $category->description }}">
                                        <input type="hidden" name="image_url" value="{{ $category->image_url }}">
                                        <button type="submit" class="text-button">Refresh slug</button>
                                    </form>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="admin-inline-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-button">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
