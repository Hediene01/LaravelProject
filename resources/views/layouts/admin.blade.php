<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel | Luna Commerce')</title>
    <link rel="stylesheet" href="{{ asset('css/store.css') }}">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a class="brand admin-brand" href="{{ route('admin.dashboard') }}">
                <span class="brand-mark">AD</span>
                <span>
                    <strong>Admin Panel</strong>
                    <small>Luna Commerce</small>
                </span>
            </a>

            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.product.index') }}">Products</a>
                <a href="{{ route('admin.categories.index') }}">Categories</a>
                <a href="{{ route('admin.brands.index') }}">Brands</a>
                <a href="{{ route('admin.orders.index') }}">Orders</a>
                <a href="{{ route('admin.reviews.index') }}">Reviews</a>
                <a href="{{ route('home') }}">Back to storefront</a>
            </nav>

            <div class="admin-user-card">
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ auth()->user()->email }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="button button-dark button-block">Log out</button>
                </form>
            </div>
        </aside>

        <main class="admin-main">
            @if (session('success'))
                <div class="flash success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="flash error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="flash error">{{ $errors->first() }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
