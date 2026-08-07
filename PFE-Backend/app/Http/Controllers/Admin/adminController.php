<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class adminController extends Controller
{

    // Tableau de bord admin
    public function dashboard()
    {
        $stats = [
            'clients'  => User::whereHas('roles', fn($q) => $q->where('name', 'client'))->count(),
            'analysts' => User::whereHas('roles', fn($q) => $q->where('name', 'analyst'))->count(),
            'pending'  => User::where('account_status', 'pending')->count(),
            'active'   => User::where('account_status', 'active')->count(),
            'inactive' => User::where('account_status', 'inactive')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // Tableau des analystes
    public function analysts()
    {
        $analystes = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'analyst');
            })
            ->latest()
            ->paginate(15);

        return view('admin.analyst.index', compact('analystes'));
    }

    // Tableau des clients
    public function clients()
    {
        $clients = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'client');
            })
            ->latest()
            ->paginate(15);

        return view('admin.client.index', compact('clients'));
    }

    // Formulaire d'édition (commun aux deux types)
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.edit', compact('user'));
    }

    // Mise à jour (commun aux deux types)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone'        => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return back()->with('success', 'Utilisateur mis à jour.');
    }

// Suppression (commun aux deux types)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Utilisateur supprimé.');
    }

    /**
     * Activer un compte depuis l'email d'approbation de l'admin (URL signée).
     */
    public function activate(Request $request, $id, $role)
    {
        // Empêche les valeurs de rôle arbitraires.
        if (! in_array($role, ['client', 'analyst'], true)) {
            abort(404);
        }

        $user = User::findOrFail($id);

        if ($user->account_status === 'active') {
            return redirect()->route('admin.dashboard')->with('success', 'Ce compte est déjà actif.');
        }

        $user->account_status = 'active';
        $user->activated_at = now();
        $user->save();

        // Envoie l'email de vérification au client/analyste.
        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::warning('Impossible d\'envoyer l\'email de vérification : ' . $e->getMessage());
        }

        $label = $role === 'analyst' ? 'Analyste' : 'Client';

        return redirect()->route('admin.dashboard')->with('success', "Compte {$label} activé avec succès. Un email de vérification lui a été envoyé.");
    }
}
