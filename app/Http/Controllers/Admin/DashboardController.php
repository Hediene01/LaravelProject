<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'productCount' => Product::query()->count(),
            'categoryCount' => Category::query()->count(),
            'orderCount' => Order::query()->count(),
            'userCount' => User::query()->count(),
            'latestProducts' => Product::query()->with(['category', 'user'])->latest()->take(5)->get(),
        ]);
    }
}
