<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class adminController extends Controller
{

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
}
