<?php

use App\Http\Controllers\Api\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [CatalogController::class, 'products']);
Route::get('/products/featured', [CatalogController::class, 'featured']);
Route::get('/products/{product}', [CatalogController::class, 'show']);
Route::get('/categories', [CatalogController::class, 'categories']);
Route::get('/brands', [CatalogController::class, 'brands']);
