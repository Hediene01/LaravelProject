<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('storefront.cart.index', compact('cartItems'));
    }

    public function add(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($productId);
        $quantity = $request->quantity ?? 1;

        if ($product->inventory < $quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            $newQuantity = $cart->quantity + $quantity;
            if ($product->inventory < $newQuantity) {
                return back()->with('error', 'Not enough stock available.');
            }
            $cart->update([
                'quantity' => $newQuantity,
                'price' => $product->price,
            ]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function update(Request $request, $cartId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', Auth::id())
            ->findOrFail($cartId);

        if ($cart->product->inventory < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated.');
    }

    public function remove($cartId)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->findOrFail($cartId);

        $cart->delete();

        return back()->with('success', 'Product removed from cart.');
    }
}
