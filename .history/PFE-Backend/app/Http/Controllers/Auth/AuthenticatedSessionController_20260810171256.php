<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->email)->first();

        if ($user && in_array($user->account_status, ['pending', 'inactive'])) {
            return back()->withErrors([
                'email' => 'Votre compte n\'est pas encore activé. Veuillez attendre la validation par un administrateur.',
            ]);
        }

        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->withErrors(['email' => 'Unable to authenticate user.']);
        }

        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        if ($user->hasRole('analyst')) {
            return redirect()->intended(route('analyst.dashboard', absolute: false));
        }

        if ($user->hasRole('client')) {
            return redirect()->intended(route('client.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
