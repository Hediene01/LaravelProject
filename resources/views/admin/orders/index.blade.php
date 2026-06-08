@extends('layouts.admin')

@section('title', 'Manage Orders | Admin Panel')

@section('content')
    <section class="page-heading">
        <div>
            <span class="eyebrow">Order management</span>
            <h1>Orders</h1>
        </div>
    </section>

    <section class="admin-panel-card">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="admin-filter-form" style="margin-bottom:1rem;display:flex;gap:.5rem;align-items:center;">
            <select name="status" class="input-field" style="max-width:200px;">
                <option value="">All Orders</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s }}" @selected($status == $s)>{{ $s }}</option>
                @endforeach
            </select>
            <button type="submit" class="button button-dark">Filter</button>
            <a href="{{ route('admin.orders.index') }}" class="button button-light">Clear</a>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->customer_email }}</td>
                            <td>${{ number_format((float) $order->total_amount, 2) }}</td>
                            <td>
                                @php
                                    $badge = match($order->status) {
                                        'New'        => 'badge-primary',
                                        'Accepted'   => 'badge-info',
                                        'Onshipping' => 'badge-warning',
                                        'Completed'  => 'badge-success',
                                        'Cancelled'  => 'badge-danger',
                                        default      => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">{{ $order->status }}</span>
                            </td>
                            <td>{{ $order->created_at->format('m/d/Y H:i') }}</td>
                            <td>
                                <a class="text-link" href="{{ route('admin.orders.show', $order) }}">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No orders found.</td>
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
