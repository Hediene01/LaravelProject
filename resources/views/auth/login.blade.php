@extends('layouts.app')

@section('title', 'Log in | Luna Commerce')

@section('content')
    <section class="auth-shell">
        <div class="auth-card">
            <span class="eyebrow">Welcome back</span>
            <h1>Log in to your account</h1>
            <p>Access your saved cards, personal information, and a faster checkout flow.</p>

            <form action="{{ route('login.store') }}" method="POST" class="account-form">
                @csrf

                <label>
                    Email
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    @error('email')<small>{{ $message }}</small>@enderror
                </label>

                <label>
                    Password
                    <input type="password" name="password" required>
                    @error('password')<small>{{ $message }}</small>@enderror
                </label>

                <label class="checkbox-row">
                    <input type="checkbox" name="remember" value="1">
                    <span>Keep me signed in</span>
                </label>

                <button type="submit" class="button button-dark button-block">Log in</button>
            </form>

            <p class="auth-switch">
                New here?
                <a href="{{ route('register') }}">Create your account</a>
            </p>
        </div>
    </section>
@endsection
