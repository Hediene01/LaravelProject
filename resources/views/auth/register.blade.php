@extends('layouts.app')

@section('title', 'Sign up | Luna Commerce')

@section('content')
    <section class="auth-shell">
        <div class="auth-card">
            <span class="eyebrow">Create account</span>
            <h1>Sign up for Luna Commerce</h1>
            <p>Register to store personal information, manage payment cards, and streamline checkout.</p>

            <form action="{{ route('register.store') }}" method="POST" class="account-form">
                @csrf

                <label>
                    Full name
                    <input type="text" name="name" value="{{ old('name') }}" required>
                    @error('name')<small>{{ $message }}</small>@enderror
                </label>

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

                <label>
                    Confirm password
                    <input type="password" name="password_confirmation" required>
                </label>

                <button type="submit" class="button button-dark button-block">Sign up</button>
            </form>

            <p class="auth-switch">
                Already have an account?
                <a href="{{ route('login') }}">Log in</a>
            </p>
        </div>
    </section>
@endsection
