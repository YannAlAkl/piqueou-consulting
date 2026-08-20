<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
        'current_password' => ['required', 'current_password'],
        'password' => [
            'required',
            'min:8',
            'regex:/[a-z]/',      // Au moins une minuscule
            'regex:/[A-Z]/',      // Au moins une majuscule
            'regex:/[0-9]/',      // Au moins un chiffre
            'regex:/[@$!%*?&#\-_]/', // Au moins un caractère spécial
            'confirmed',
        ],
    ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
