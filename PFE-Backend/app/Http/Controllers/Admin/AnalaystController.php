<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AnalystAccountMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AnalaystController extends Controller
{
    public function index()
    {
        $analystes = User::whereHas('roles', fn($q) => $q->where('name', 'analyst'))
            ->latest()
            ->paginate(10);

        return view('admin.analyst.index', compact('analystes'));
    }

    public function create()
    {
        return view('admin.analyst.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255|unique:users,email',
            'phone'          => 'nullable|string|max:30',
            'company_name'   => 'nullable|string|max:255',
            'account_status' => 'nullable|in:pending,active,inactive',
        ]);

        $statut = $validated['account_status'] ?? 'active';
        $motDePasse = Str::random(10);

        $analyst = User::create([
            'first_name'     => $validated['first_name'],
            'last_name'      => $validated['last_name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($motDePasse),
            'phone'          => $validated['phone'] ?? null,
            'company_name'   => $validated['company_name'] ?? null,
            'account_status' => $statut,
            'activated_at'   => $statut === 'active' ? now() : null,
        ]);

        $analyst->assignRole('analyst');

        $message = 'Analyste ajouté. Ses identifiants lui ont été envoyés par email.';

        try {
            Mail::to($analyst->email)->send(new AnalystAccountMail($analyst, $motDePasse));
        } catch (\Exception $e) {
            $message = 'Analyste ajouté mais l\'email n\'a pas pu être envoyé. Mot de passe : ' . $motDePasse;
        }

        return redirect()->route('admin.analyst.index')->with('success', $message);
    }

    public function show($id)
    {
        $analyst = User::findOrFail($id);

        return view('admin.analyst.show', compact('analyst'));
    }

    public function edit($id)
    {
        $analyst = User::findOrFail($id);

        return view('admin.analyst.edit', compact('analyst'));
    }

    public function uptade(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255|unique:users,email,' . $id,
            'phone'          => 'nullable|string|max:30',
            'company_name'   => 'nullable|string|max:255',
            'account_status' => 'nullable|in:pending,active,inactive',
        ]);

        $analyst = User::findOrFail($id);

        if (($validated['account_status'] ?? null) === 'active' && ! $analyst->activated_at) {
            $validated['activated_at'] = now();
        }

        $analyst->update($validated);

        return redirect()->route('admin.analyst.index')->with('success', 'Analyste mis à jour.');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('admin.analyst.index')->with('success', 'Analyste supprimé.');
    }

    public function verify($id)
    {
        $analyst = User::findOrFail($id);

        $analyst->account_status = 'active';
        $analyst->activated_at = now();
        $analyst->save();

        $message = 'Compte analyste activé et email de vérification envoyé.';

        try {
            $analyst->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            $message = 'Compte analyste activé mais l\'email de vérification n\'a pas pu être envoyé.';
        }

        return redirect()->route('admin.analyst.index')->with('success', $message);
    }
}
