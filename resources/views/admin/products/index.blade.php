@extends('layouts.admin')

@section('title', 'Manage Products | Admin Panel')

@section('content')
    <section class="section-heading">
        <div>
            <span class="eyebrow">CRUD products</span>
            <h1>Manage products</h1>
        </div>
        <a class="button button-dark" href="{{ route('admin.product.create') }}">Add product</a>
    </section>

    <section class="admin-panel-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category?->name }}</td>
                            <td>{{ $product->user?->name ?? 'Seeder' }}</td>
                            <td>${{ number_format((float) $product->price, 2) }}</td>
                            <td>{{ $product->inventory }}</td>
                            <td>{{ $product->is_active ? 'Active' : 'Hidden' }}</td>
                            <td class="admin-actions-cell">
                                <a class="text-link" href="{{ route('admin.product.show', $product) }}">Show</a>
                                <a class="text-link" href="{{ route('admin.product.edit', $product) }}">Edit</a>
                                <form action="{{ route('admin.product.destroy', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-button">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $products->links() }}
        </div>
    </section>
@endsection
