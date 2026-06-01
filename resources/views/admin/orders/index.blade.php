@extends('layouts.admin')

@section('title', 'Manage Orders | Admin Panel')

@section('content')
    <section class="page-heading">
        <div>
            <span class="eyebrow">Order management</span>
            <h1>Orders</h1>
            <p>Review customer purchases and update the current fulfillment status.</p>
        </div>
    </section>

    <section class="admin-panel-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>${{ number_format((float) $order->total_amount, 2) }}</td>
                            <td><a class="text-link" href="{{ route('admin.orders.show', $order) }}">View</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No orders available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $orders->links() }}
        </div>
    </section>
@endsection
