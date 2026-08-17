<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

use Illuminate\Routing\Controller as BaseController;

class VerifyEmailController extends BaseController
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));

            // Une fois le compte vérifié, son statut passe automatiquement à actif.
            // $user = $request->user();
            // if ($user->account_status === 'pending') {
            //     $user->account_status = 'active';
            //     $user->activated_at = now();
            //     $user->save();
            // }
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
