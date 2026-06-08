<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->price * $item->quantity);
        $shippingPrice = 0;
        $total = $subtotal + $shippingPrice;

        return view('storefront.checkout.create', compact(
            'cartItems',
            'subtotal',
            'shippingPrice',
            'total'
        ));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'phone'           => 'nullable|string|max:30',
            'address'         => 'required|string',
            'city'            => 'nullable|string|max:255',
            'country'         => 'nullable|string|max:255',
            'zip_code'        => 'nullable|string|max:50',
            'shipping_method' => 'required|string|max:255',
            'payment_method'  => 'required|string|max:255',
        ]);

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        DB::transaction(function () use ($request, $cartItems) {
            $subtotal = $cartItems->sum(fn ($item) => $item->price * $item->quantity);
            $shippingPrice = $request->shipping_method === 'Standard Shipping' ? 4 : 0;
            $total = $subtotal + $shippingPrice;

            $order = Order::create([
                'user_id'         => Auth::id(),
                'order_number'    => 'ORD-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
                'customer_name'   => $request->name,
                'customer_email'  => $request->email,
                'customer_phone'  => $request->phone,
                'shipping_address' => $request->address,
                'city'            => $request->city,
                'country'         => $request->country,
                'postal_code'     => $request->zip_code,
                'subtotal'        => $subtotal,
                'shipping_amount' => $shippingPrice,
                'total_amount'    => $total,
                'shipping_method' => $request->shipping_method,
                'payment_method'  => $request->payment_method,
                'status'          => 'New',
            ]);

            foreach ($cartItems as $item) {
                $product = $item->product;

                if ($product->inventory < $item->quantity) {
                    throw new \Exception($product->name.' does not have enough stock.');
                }

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'sku'          => $product->sku ?? '',
                    'price'        => $item->price,
                    'quantity'     => $item->quantity,
                    'line_total'   => $item->price * $item->quantity,
                ]);

                $product->decrement('inventory', $item->quantity);
            }

            Cart::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('home')
            ->with('success', 'Your order has been placed successfully.');
    }
}
