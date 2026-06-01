<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $user = $request->user();

        $purchased = $user->orders()
            ->whereHas('orderItems', fn ($query) => $query->where('product_id', $product->id))
            ->exists();

        abort_unless($purchased, 403, 'You can review only products you have purchased.');

        Review::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $product->id,
            ],
            [
                ...$validated,
                'order_id' => $user->orders()
                    ->whereHas('orderItems', fn ($query) => $query->where('product_id', $product->id))
                    ->latest()
                    ->value('id'),
                'status' => 'pending',
                'approved_at' => null,
            ]
        );

        $product->syncReviewMetrics();

        return back()->with('success', 'Your review was submitted for moderation.');
    }
}
