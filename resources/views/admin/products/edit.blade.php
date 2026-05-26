@extends('layouts.admin')

@section('title', 'Edit Product | Admin Panel')

@section('content')
    <section class="page-heading">
        <div>
            <span class="eyebrow">Update</span>
            <h1>Edit {{ $product->name }}</h1>
            <p>Update inventory, pricing, status, metadata, and image from the protected admin panel.</p>
        </div>
    </section>

    <form action="{{ route('admin.product.update', $product) }}" method="POST" enctype="multipart/form-data" class="admin-form-card">
        @csrf
        @method('PUT')
        @include('admin.products.partials.form', ['product' => $product, 'categories' => $categories])
    </form>
@endsection
