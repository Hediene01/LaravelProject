<?php

namespace App\Providers;

use App\Models\Wishlist;
use App\Support\Cart;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view): void {
            $view->with('cartCount', Cart::count());
            $view->with('cartSubtotal', Cart::subtotal());
            $view->with('wishlistCount', auth()->check()
                ? Wishlist::query()->where('user_id', auth()->id())->count()
                : 0);
        });
    }
}
