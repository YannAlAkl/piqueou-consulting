public function update(Request $request): RedirectResponse
{
    // On définit explicitement l'objet de règle de mot de passe
    $passwordRules = Password::min(8)
        ->letters()
        ->mixedCase()
        ->numbers()
        ->symbols();

    $validated = $request->validateWithBag('updatePassword', [
        'current_password' => ['required', 'current_password'],
        'password' => ['required', 'confirmed', $passwordRules],
    ]);

    $request->user()->update([
        'password' => Hash::make($validated['password']),
    ]);

    return back()->with('status', 'password-updated');
}
