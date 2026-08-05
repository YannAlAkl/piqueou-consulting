<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Traits\HasRoles;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if($user->hasRole('admin')){
            $redirectRoute = redirect()->route('analyst.index');
        }elseIf($user->hasRole('analyst')){
            $redirectRoute = redirect()->route('analyst.dashboard');
        }elseIf($user->hasRole('client')){
            $redirectRoute =   redirect()->route('client.dashboard');
        }else{
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'You do not have permission to access this application.']);
        }
        return redirect()->intended($redirectRoute)
;    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
