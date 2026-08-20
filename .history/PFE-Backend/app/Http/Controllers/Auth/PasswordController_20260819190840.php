public function update(Request $request): RedirectResponse
{
    $validated = $request->validateWithBag('updatePassword', [
        'current_password' => ['required', 'current_password'],
        'password' => [
            'required',
            'confirmed',
            'min:8',             // 8 caractères minimum
            'regex:/[a-z]/',     // Au moins une minuscule
            'regex:/[A-Z]/',     // Au moins une majuscule
            'regex:/[0-9]/',     // Au moins un chiffre
            'regex:/[@$!%*?&#\-_]/' // Au moins un symbole
        ],
    ]);

    $request->user()->update([
        'password' => Hash::make($validated['password']),
    ]);

    return back()->with('status', 'password-updated');
}
