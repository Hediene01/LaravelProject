@extends('layouts.app')

@section('title', 'My Account | Luna Commerce')

@section('content')
    <section class="page-heading">
        <div>
            <span class="eyebrow">User account</span>
            <h1>My account</h1>
            <p>Manage your login details, personal information, and saved payment cards.</p>
        </div>
    </section>

    <section class="account-layout">
        <div class="account-section">
            <div class="account-panel">
                <span class="eyebrow">Personal information</span>
                <h2>Your profile</h2>

                <form action="{{ route('account.update') }}" method="POST" class="account-form">
                    @csrf
                    @method('PATCH')

                    <div class="field-grid">
                        <label>
                            Full name
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')<small>{{ $message }}</small>@enderror
                        </label>

                        <label>
                            Email
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')<small>{{ $message }}</small>@enderror
                        </label>

                        <label>
                            Phone
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')<small>{{ $message }}</small>@enderror
                        </label>

                        <label>
                            City
                            <input type="text" name="city" value="{{ old('city', $user->city) }}">
                            @error('city')<small>{{ $message }}</small>@enderror
                        </label>

                        <label>
                            Postal code
                            <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
                            @error('postal_code')<small>{{ $message }}</small>@enderror
                        </label>

                        <label>
                            Country
                            <input type="text" name="country" value="{{ old('country', $user->country) }}">
                            @error('country')<small>{{ $message }}</small>@enderror
                        </label>
                    </div>

                    <label>
                        Address
                        <input type="text" name="address_line" value="{{ old('address_line', $user->address_line) }}">
                        @error('address_line')<small>{{ $message }}</small>@enderror
                    </label>

                    <button type="submit" class="button button-dark">Save personal information</button>
                </form>
            </div>
        </div>

        <div class="account-section">
            <div class="account-panel">
                <span class="eyebrow">Saved cards</span>
                <h2>Payment cards</h2>
                <p class="account-note">For safety, the app stores only masked card details, brand, expiry, and cardholder name.</p>

                <div class="saved-card-list">
                    @forelse ($user->savedCards as $savedCard)
                        <article class="saved-card">
                            <div>
                                <div class="saved-card-top">
                                    <strong>{{ $savedCard->brand }}</strong>
                                    @if ($savedCard->is_default)
                                        <span class="chip chip-accent">Default</span>
                                    @endif
                                </div>
                                <p>{{ $savedCard->cardholder_name }}</p>
                                <span>•••• {{ $savedCard->last_four }} · {{ str_pad((string) $savedCard->expiry_month, 2, '0', STR_PAD_LEFT) }}/{{ $savedCard->expiry_year }}</span>
                            </div>

                            <div class="saved-card-actions">
                                @if (! $savedCard->is_default)
                                    <form action="{{ route('account.cards.update', $savedCard) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_default" value="1">
                                        <button type="submit" class="text-button">Set as default</button>
                                    </form>
                                @endif

                                <form action="{{ route('account.cards.destroy', $savedCard) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-button">Remove</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="empty-inline">
                            No saved cards yet. Add one below to create a personal payment section for the user.
                        </div>
                    @endforelse
                </div>

                <form action="{{ route('account.cards.store') }}" method="POST" class="account-form">
                    @csrf

                    <div class="field-grid">
                        <label>
                            Cardholder name
                            <input type="text" name="cardholder_name" value="{{ old('cardholder_name', $user->name) }}" required>
                            @error('cardholder_name')<small>{{ $message }}</small>@enderror
                        </label>

                        <label>
                            Card number
                            <input type="text" name="card_number" value="{{ old('card_number') }}" placeholder="4242 4242 4242 4242" required>
                            @error('card_number')<small>{{ $message }}</small>@enderror
                        </label>

                        <label>
                            Expiry month
                            <input type="number" name="expiry_month" value="{{ old('expiry_month') }}" min="1" max="12" required>
                            @error('expiry_month')<small>{{ $message }}</small>@enderror
                        </label>

                        <label>
                            Expiry year
                            <input type="number" name="expiry_year" value="{{ old('expiry_year') }}" min="{{ now()->year }}" max="{{ now()->year + 15 }}" required>
                            @error('expiry_year')<small>{{ $message }}</small>@enderror
                        </label>
                    </div>

                    <label class="checkbox-row">
                        <input type="checkbox" name="is_default" value="1" @checked(old('is_default'))>
                        <span>Use as my default card</span>
                    </label>

                    <button type="submit" class="button button-dark">Save card</button>
                </form>
            </div>
        </div>
    </section>
@endsection
