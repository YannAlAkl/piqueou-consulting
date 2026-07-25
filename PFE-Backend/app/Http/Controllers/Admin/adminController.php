<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class adminController extends Controller
{
    // Dashboard admin
    public function index(Request $request): View
    {
        $roleFilter = $request->string('role')->toString();
        $statusFilter = $request->string('status')->toString();
        $search = $request->string('search')->trim()->toString();

        $query = User::with('roles');

        if ($roleFilter === 'client') {
            $query->whereHas('roles', fn ($q) => $q->where('name', 'client'));
        } elseif ($roleFilter === 'analyst') {
            $query->whereHas('roles', fn ($q) => $q->where('name', 'analyst'));
        } else {
            $query->whereHas('roles', fn ($q) => $q->whereIn('name', ['client', 'analyst']));
        }

        if (in_array($statusFilter, ['pending', 'active', 'inactive'], true)) {
            $query->where('account_status', $statusFilter);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        if ($roleFilter === 'client') {
            return view('admin.client.index', compact('users', 'roleFilter', 'statusFilter', 'search'));
        } elseif ($roleFilter === 'analyst') {
            return view('admin.analyst.index', compact('users', 'roleFilter', 'statusFilter', 'search'));
        }

        $managedUsersQuery = User::whereHas('roles', fn ($q) => $q->whereIn('name', ['client', 'analyst']));

        $stats = [
            'clients' => (clone $managedUsersQuery)->whereHas('roles', fn ($q) => $q->where('name', 'client'))->count(),
            'analysts' => (clone $managedUsersQuery)->whereHas('roles', fn ($q) => $q->where('name', 'analyst'))->count(),
            'pending' => (clone $managedUsersQuery)->where('account_status', 'pending')->count(),
            'active' => (clone $managedUsersQuery)->where('account_status', 'active')->count(),
            'inactive' => (clone $managedUsersQuery)->where('account_status', 'inactive')->count(),
        ];

        return view('admin.dashboard', compact('users', 'stats', 'roleFilter', 'statusFilter', 'search'));
    }

    // Tableau des analystes
    public function analyst()
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
    public function client()
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

