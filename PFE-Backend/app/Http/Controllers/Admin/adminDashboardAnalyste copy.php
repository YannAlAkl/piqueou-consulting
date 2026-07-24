<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $roleFilter = $request->string('role')->toString();
        $statusFilter = $request->string('status')->toString();
        $search = $request->string('search')->trim()->toString();

        $query = User::with('roles')
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['client', 'analyst']));

        if (in_array($roleFilter, ['client', 'analyst'], true)) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $roleFilter));
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
}
