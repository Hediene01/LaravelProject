<?php

namespace App\Http\Controllers;

use App\Models\SavedCard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SavedCardController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cardholder_name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'string', 'min:12', 'max:23'],
            'expiry_month' => ['required', 'integer', 'between:1,12'],
            'expiry_year' => ['required', 'integer', 'min:'.now()->year, 'max:'.(now()->year + 15)],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $digits = preg_replace('/\D+/', '', $validated['card_number']);

        if (strlen($digits) < 12) {
            return back()->withErrors([
                'card_number' => 'Please enter a valid card number.',
            ])->withInput();
        }

        $user = $request->user();
        $isDefault = $request->boolean('is_default') || $user->savedCards()->count() === 0;

        if ($isDefault) {
            $user->savedCards()->update(['is_default' => false]);
        }

        $user->savedCards()->create([
            'cardholder_name' => $validated['cardholder_name'],
            'brand' => $this->detectBrand($digits),
            'last_four' => substr($digits, -4),
            'expiry_month' => $validated['expiry_month'],
            'expiry_year' => $validated['expiry_year'],
            'is_default' => $isDefault,
        ]);

        return back()->with('success', 'Card saved to your account.');
    }

    public function update(Request $request, SavedCard $savedCard): RedirectResponse
    {
        abort_unless($savedCard->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'is_default' => ['required', 'boolean'],
        ]);

        if ((bool) $validated['is_default']) {
            $request->user()->savedCards()->update(['is_default' => false]);
            $savedCard->update(['is_default' => true]);
        }

        return back()->with('success', 'Default card updated.');
    }

    public function destroy(Request $request, SavedCard $savedCard): RedirectResponse
    {
        abort_unless($savedCard->user_id === $request->user()->id, 403);

        $wasDefault = $savedCard->is_default;
        $savedCard->delete();

        if ($wasDefault) {
            $request->user()->savedCards()->oldest()->first()?->update(['is_default' => true]);
        }

        return back()->with('success', 'Card removed from your account.');
    }

    private function detectBrand(string $digits): string
    {
        return match (true) {
            str_starts_with($digits, '4') => 'Visa',
            preg_match('/^5[1-5]/', $digits) === 1 => 'Mastercard',
            preg_match('/^3[47]/', $digits) === 1 => 'American Express',
            preg_match('/^6(?:011|5)/', $digits) === 1 => 'Discover',
            default => 'Card',
        };
    }
}
