@extends('layouts.admin')

@section('title', 'Manage Brands | Admin Panel')

@section('content')
    <section class="page-heading">
        <div>
            <span class="eyebrow">Catalog enrichment</span>
            <h1>Brands</h1>
            <p>Add brand identity, logos, and product grouping for the storefront and API.</p>
        </div>
    </section>

    <section class="admin-split-grid">
        <div class="admin-form-card">
            <h2>Create brand</h2>
            <form action="{{ route('admin.brands.store') }}" method="POST" class="admin-form-card-inner">
                @csrf
                <label>
                    Name
                    <input type="text" name="name" required>
                </label>
                <label>
                    Description
                    <textarea name="description" rows="4"></textarea>
                </label>
                <label>
                    Logo URL
                    <input type="text" name="logo_url">
                </label>
                <button type="submit" class="button button-dark">Create brand</button>
            </form>
        </div>

        <div class="admin-panel-card">
            <h2>Existing brands</h2>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Products</th>
                            <th>Logo</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>{{ $brand->name }}</td>
                                <td>{{ $brand->products_count }}</td>
                                <td>{{ $brand->logo_url ? 'Yes' : 'No' }}</td>
                                <td class="admin-actions-cell">
                                    <form action="{{ route('admin.brands.update', $brand) }}" method="POST" class="admin-inline-form">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" value="{{ $brand->name }}">
                                        <input type="hidden" name="description" value="{{ $brand->description }}">
                                        <input type="hidden" name="logo_url" value="{{ $brand->logo_url }}">
                                        <button type="submit" class="text-button">Refresh slug</button>
                                    </form>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="admin-inline-form">
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
