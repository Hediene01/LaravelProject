<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

class Cart
{
    public static function content(): Collection
    {
        return collect(session('cart', []))->map(function (array $item): array {
            $item['line_total'] = $item['price'] * $item['quantity'];

            return $item;
        });
    }

    public static function count(): int
    {
        return (int) static::content()->sum('quantity');
    }

    public static function subtotal(): float
    {
        return (float) static::content()->sum('line_total');
    }

    public static function shippingAmount(): float
    {
        if (static::count() === 0) {
            return 0.0;
        }

        return static::subtotal() >= 200 ? 0.0 : 15.0;
    }

    public static function total(): float
    {
        return static::subtotal() + static::shippingAmount();
    }

    public static function add(Product $product, int $quantity): void
    {
        $cart = session('cart', []);

        $existing = $cart[$product->id]['quantity'] ?? 0;
        $newQuantity = min($existing + $quantity, $product->inventory);

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'price' => (float) $product->price,
            'image_url' => $product->image_url,
            'quantity' => max(1, $newQuantity),
        ];

        session(['cart' => $cart]);
    }

    public static function update(Product $product, int $quantity): void
    {
        $cart = session('cart', []);

        if (! isset($cart[$product->id])) {
            return;
        }

        if ($quantity <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id]['quantity'] = min($quantity, $product->inventory);
        }

        session(['cart' => $cart]);
    }

    public static function remove(Product $product): void
    {
        $cart = session('cart', []);

        unset($cart[$product->id]);

        session(['cart' => $cart]);
    }

    public static function clear(): void
    {
        session()->forget('cart');
    }
}
