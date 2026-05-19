<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\Cart;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Cart::count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('storefront.checkout.create', [
            'items' => Cart::content(),
            'subtotal' => Cart::subtotal(),
            'shippingAmount' => Cart::shippingAmount(),
            'total' => Cart::total(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (Cart::count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Add products before checkout.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'postal_code' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = DB::transaction(function () use ($validated) {
            $order = Order::create([
                ...$validated,
                'order_number' => 'ORD-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
                'subtotal' => Cart::subtotal(),
                'shipping_amount' => Cart::shippingAmount(),
                'total_amount' => Cart::total(),
                'status' => 'pending',
            ]);

            foreach (Cart::content() as $item) {
                $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'sku' => $item['sku'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);
            }

            return $order;
        });

        Cart::clear();

        return redirect()
            ->route('checkout.success', $order)
            ->with('success', 'Order placed successfully.');
    }

    public function success(Order $order): View
    {
        return view('storefront.checkout.success', [
            'order' => $order->load('orderItems'),
        ]);
    }
}
