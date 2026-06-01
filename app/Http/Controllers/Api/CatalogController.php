<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function products(Request $request): JsonResponse
    {
        $products = Product::query()
            ->with(['category', 'brand'])
            ->where('is_active', true)
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q')->toString().'%';
                $query->where(function ($innerQuery) use ($term) {
                    $innerQuery->where('name', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhere('keywords', 'like', $term)
                        ->orWhere('sku', 'like', $term);
                });
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $request->string('category')->toString()));
            })
            ->when($request->filled('brand'), function ($query) use ($request) {
                $query->whereHas('brand', fn ($brandQuery) => $brandQuery->where('slug', $request->string('brand')->toString()));
            })
            ->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', (float) $request->input('min_price')))
            ->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', (float) $request->input('max_price')))
            ->paginate((int) min(50, max(1, (int) $request->input('per_page', 12))));

        return response()->json($products);
    }

    public function featured(): JsonResponse
    {
        return response()->json(
            Product::query()
                ->with(['category', 'brand'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->latest()
                ->take(6)
                ->get()
        );
    }

    public function show(Product $product): JsonResponse
    {
        abort_unless($product->is_active, 404);

        return response()->json(
            $product->load([
                'category',
                'brand',
                'approvedReviews.user',
            ])
        );
    }

    public function categories(): JsonResponse
    {
        return response()->json(
            Category::query()
                ->withCount('products')
                ->with('children')
                ->orderBy('name')
                ->get()
        );
    }

    public function brands(): JsonResponse
    {
        return response()->json(
            Brand::query()
                ->withCount('products')
                ->orderBy('name')
                ->get()
        );
    }
}
