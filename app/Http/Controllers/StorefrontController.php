<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function home(): View
    {
        return view('storefront.home', [
            'categories' => Category::query()
                ->withCount('products')
                ->orderBy('name')
                ->get(),
            'brands' => Brand::query()
                ->withCount('products')
                ->orderBy('name')
                ->take(6)
                ->get(),
            'featuredProducts' => Product::query()
                ->with(['category', 'brand'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->latest()
                ->take(6)
                ->get(),
            'newArrivals' => Product::query()
                ->with(['category', 'brand'])
                ->where('is_active', true)
                ->latest()
                ->take(4)
                ->get(),
        ]);
    }

    public function products(Request $request): View
    {
        $selectedCategory = $request->string('category')->toString();
        $selectedBrand = $request->string('brand')->toString();
        $search = $request->string('search')->toString();

        $products = Product::query()
            ->with(['category', 'brand'])
            ->where('is_active', true)
            ->when($selectedCategory !== '', function ($query) use ($selectedCategory) {
                $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $selectedCategory));
            })
            ->when($selectedBrand !== '', function ($query) use ($selectedBrand) {
                $query->whereHas('brand', fn ($brandQuery) => $brandQuery->where('slug', $selectedBrand));
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('keywords', 'like', '%'.$search.'%')
                        ->orWhere('sku', 'like', '%'.$search.'%');
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('storefront.products.index', [
            'products' => $products,
            'categories' => Category::query()->orderBy('name')->get(),
            'brands' => Brand::query()->orderBy('name')->get(),
            'selectedCategory' => $selectedCategory,
            'selectedBrand' => $selectedBrand,
            'search' => $search,
        ]);
    }

    public function show(Request $request, Product $product): View
    {
        abort_unless($product->is_active, 404);

        $canReview = false;

        if ($request->user()) {
            $canReview = $request->user()->orders()
                ->whereHas('orderItems', fn ($query) => $query->where('product_id', $product->id))
                ->exists();
        }

        return view('storefront.products.show', [
            'product' => $product->load(['category.parent', 'brand', 'approvedReviews.user']),
            'relatedProducts' => Product::query()
                ->with(['category', 'brand'])
                ->where('is_active', true)
                ->where('category_id', $product->category_id)
                ->whereKeyNot($product->id)
                ->latest()
                ->take(3)
                ->get(),
            'canReview' => $canReview,
        ]);
    }
}
