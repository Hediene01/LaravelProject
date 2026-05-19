<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(): View
    {
        return view('storefront.cart.index', [
            'items' => Cart::content(),
            'subtotal' => Cart::subtotal(),
            'shippingAmount' => Cart::shippingAmount(),
            'total' => Cart::total(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::query()->where('is_active', true)->findOrFail($validated['product_id']);

        Cart::add($product, $validated['quantity']);

        return back()->with('success', $product->name.' added to cart.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        Cart::update($product, $validated['quantity']);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        Cart::remove($product);

        return back()->with('success', 'Item removed from cart.');
    }
}
