@extends('layouts.admin')

@section('title', 'Admin Dashboard | Luna Commerce')

@section('content')
    <section class="page-heading">
        <div>
            <span class="eyebrow">Protected admin area</span>
            <h1>Store dashboard</h1>
            <p>Manage the storefront, review customer activity, and moderate catalog content from one place.</p>
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
            <span>Brands</span>
            <strong>{{ $brandCount }}</strong>
        </article>
        <article class="admin-stat-card">
            <span>Orders</span>
            <strong>{{ $orderCount }}</strong>
        </article>
        <article class="admin-stat-card">
            <span>Users</span>
            <strong>{{ $userCount }}</strong>
        </article>
        <article class="admin-stat-card">
            <span>Pending reviews</span>
            <strong>{{ $pendingReviewCount }}</strong>
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
                    @forelse ($latestProducts as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category?->name }}</td>
                            <td>{{ $product->user?->name ?? 'Seeder' }}</td>
                            <td>{{ $product->is_active ? 'Active' : 'Hidden' }}</td>
                            <td>${{ number_format((float) $product->price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No products available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="admin-panel-card">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Recent commerce activity</span>
                <h2>Latest orders</h2>
            </div>
            <a class="button button-light" href="{{ route('admin.orders.index') }}">Open orders</a>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestOrders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>${{ number_format((float) $order->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
