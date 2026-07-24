<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardClientController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->string('status')->toString();
        $search = $request->string('search')->toString();

        $query = User::where('role', 'client');

        if ($statusFilter !== '') {
            $query->where('account_status', $statusFilter);
        }

        if ($search !== '') {
            $query->where('first_name', 'like', '%' . $search . '%');
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.dashboard-client', compact('users', 'statusFilter', 'search'));
    }
};
