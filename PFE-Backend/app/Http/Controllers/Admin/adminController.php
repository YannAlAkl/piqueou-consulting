<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class adminController extends Controller
{

    // Montre à l'administrateur la liste des dossiers et la liste des analystes
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

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone'        => 'nullable|string|max:30',
            'company_name' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->update($validated);

        return back()->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Utilisateur supprimé.');
    }

    public function activate( $id, $role)
    {
        if (! in_array($role, ['client', 'analyst'])) {
            abort(404);
        }

        $user = User::findOrFail($id);

        if ($user->account_status === 'active') {
            return redirect()->route('login')->with('status', 'Ce compte est déjà actif.');
        }

        $user->account_status = 'active';
        $user->activated_at = now();
        $user->save();

        $message = 'Le compte a été activé. Un email de vérification a été envoyé.';

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            $message = 'Le compte a été activé mais l\'email de vérification n\'a pas pu être envoyé.';
        }

        return redirect()->route('login')->with('status', $message);
    }
}
