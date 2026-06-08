<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        $orders = Order::with('user')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statuses = Order::STATUSES;

        return view('admin.orders.index', compact('orders', 'statuses', 'status'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);
        $statuses = Order::STATUSES;

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:'.implode(',', Order::STATUSES),
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated successfully.');
    }
}
