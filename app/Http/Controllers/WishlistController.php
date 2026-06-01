<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        return view('account.wishlist', [
            'wishlistItems' => $request->user()
                ->wishlistItems()
                ->with('product.category', 'product.brand')
                ->get(),
        ]);
    }

    public function toggle(Request $request, Product $product): RedirectResponse
    {
        $existing = $request->user()
            ->wishlistItems()
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return back()->with('success', 'Product removed from wishlist.');
        }

        $request->user()->wishlistItems()->create([
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Product added to wishlist.');
    }
}
