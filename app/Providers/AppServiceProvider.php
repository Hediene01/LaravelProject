<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view): void {
            if (auth()->check()) {
                $cartItems = Cart::where('user_id', auth()->id())->get();
                $view->with('cartCount', $cartItems->sum('quantity'));
                $view->with('cartSubtotal', $cartItems->sum(fn ($i) => $i->price * $i->quantity));
            } else {
                $view->with('cartCount', 0);
                $view->with('cartSubtotal', 0);
            }

            $view->with('wishlistCount', auth()->check()
                ? Wishlist::query()->where('user_id', auth()->id())->count()
                : 0);
        });

        View::composer('layouts.app', function ($view): void {
            $view->with('categories', Category::where('parent_id', 0)
                ->with('children')
                ->get());
        });
    }
}
