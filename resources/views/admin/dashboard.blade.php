@extends('layouts.admin')

@section('title', 'Admin Dashboard | Luna Commerce')

@section('content')
    <section class="page-heading">
        <div>
            <span class="eyebrow">Protected admin area</span>
            <h1>Store dashboard</h1>
            <p>Accessible only to users with the <strong>admin</strong> role, as requested in the course PDFs.</p>
        </div>
    </section>

    <section class="admin-stats">
        <article class="admin-stat-card">
            <span>Products</span>
            <strong>{{ $productCount }}</strong>
        </article>
        <article class="admin-stat-card">
            <span>Categories</span>
            <strong>{{ $categoryCount }}</strong>
        </article>
        <article class="admin-stat-card">
            <span>Orders</span>
            <strong>{{ $orderCount }}</strong>
        </article>
        <article class="admin-stat-card">
            <span>Users</span>
            <strong>{{ $userCount }}</strong>
        </article>
    </section>

    <section class="admin-panel-card">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Latest content</span>
                <h2>Recently added products</h2>
            </div>
            <a class="button button-dark" href="{{ route('admin.product.create') }}">Create product</a>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($latestProducts as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category?->name }}</td>
                            <td>{{ $product->user?->name ?? 'Seeder' }}</td>
                            <td>{{ $product->is_active ? 'Active' : 'Hidden' }}</td>
                            <td>${{ number_format((float) $product->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
