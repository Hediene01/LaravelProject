@extends('layouts.admin')

@section('title', 'Create Product | Admin Panel')

@section('content')
    <section class="page-heading">
        <div>
            <span class="eyebrow">Create</span>
            <h1>Create a new product</h1>
            <p>Admin-only product form based on the CRUD lesson structure from the PDF.</p>
        </div>
    </section>

    <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" class="admin-form-card">
        @csrf
        @include('admin.products.partials.form', ['product' => $product, 'categories' => $categories])
    </form>
@endsection
