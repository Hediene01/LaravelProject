<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::query()->create($validated);

        $userRole = Role::query()->where('name', 'user')->first();

        if ($userRole !== null) {
            $user->roles()->syncWithoutDetaching([$userRole->id]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('account.show')->with('success', 'Your account has been created.');
    }
}
