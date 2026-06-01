<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::query()
                ->with(['brand', 'category', 'user'])
                ->latest()
                ->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'product' => new Product(),
            'brands' => Brand::query()->orderBy('name')->get(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $payload = collect($validated)->except('image')->all();
        $payload['gallery'] = $this->galleryFromInput($validated['gallery_input'] ?? null);
        unset($payload['gallery_input']);

        Product::query()->create([
            ...$payload,
            'slug' => $this->uniqueSlug($payload['name']),
            'user_id' => $request->user()->id,
            'image_url' => $this->storeImage($request) ?? $payload['image_url'],
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.product.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        return view('admin.products.show', [
            'product' => $product->load(['brand', 'category', 'user']),
        ]);
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'brands' => Brand::query()->orderBy('name')->get(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validatedData($request, $product);
        $payload = collect($validated)->except('image')->all();
        $payload['gallery'] = $this->galleryFromInput($validated['gallery_input'] ?? null);
        unset($payload['gallery_input']);
        $uploadedImage = $this->storeImage($request);

        if ($uploadedImage !== null) {
            $this->deleteLocalImage($product->image_url);
        }

        $product->update([
            ...$payload,
            'slug' => $this->uniqueSlug($payload['name'], $product->id),
            'user_id' => $product->user_id ?? $request->user()->id,
            'image_url' => $uploadedImage ?? $payload['image_url'] ?? $product->image_url,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.product.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteLocalImage($product->image_url);
        $product->delete();

        return redirect()
            ->route('admin.product.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function validatedData(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:255'],
            'keywords' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'detail' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'gallery_input' => ['nullable', 'string', 'max:5000'],
            'sku' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'sku')->ignore($product?->id),
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'inventory' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'discount' => ['required', 'integer', 'min:0', 'max:100'],
            'image_url' => $product === null
                ? ['required_without:image', 'nullable', 'string', 'max:2048']
                : ['nullable', 'string', 'max:2048'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $baseSlug = $slug;
        $suffix = 2;

        while (
            Product::query()
                ->where('slug', $slug)
                ->when($ignoreId !== null, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $directory = public_path('uploads/products');
        File::ensureDirectoryExists($directory);

        $filename = Str::uuid()->toString().'.'.$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move($directory, $filename);

        return '/uploads/products/'.$filename;
    }

    private function deleteLocalImage(?string $path): void
    {
        if (! is_string($path) || ! str_starts_with($path, '/uploads/products/')) {
            return;
        }

        File::delete(public_path(ltrim($path, '/')));
    }

    private function galleryFromInput(?string $galleryInput): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $galleryInput) ?: [])
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
