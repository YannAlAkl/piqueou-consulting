<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AnalaystController extends Controller
{
   public function index(Request $request)
    {
        $analystes = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'analyst');
        })->get();

        return view('admin.analyst.index', compact('analystes'));
    }

    public function create()
    {
        return view('admin.analyst.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8|confirmed',
            'phone'      => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'account_status' => 'nullable|in:pending,active,inactive',
        ]);

        $analyst = User::create([
            'first_name'     => $validated['first_name'],
            'last_name'      => $validated['last_name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),
            'phone'          => $validated['phone'] ?? null,
            'company_name'   => $validated['company_name'] ?? null,
            'account_status' => $validated['account_status'] ?? 'pending',
        ]);

        $role = Role::where('name', 'analyst')->first();
        $analyst->roles()->attach($role);

        return redirect()->route('admin.analyst.index')->with('success', 'Analyste créé avec succès.');
    }

    public function show($id)
    {
        $analyst = User::with('roles')->findOrFail($id);
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
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users,email,' . $id,
        ]);

        $analyst = User::findOrFail($id);
        $analyst->update($validated);

        return redirect()->route('admin.analyst.index')->with('success', 'Analyste mis à jour avec succès.');
    }
    public function destroy($id)
    {
        $analyst = User::findOrFail($id);
        $analyst->delete();

        return redirect()->route('admin.analyst.index')->with('success', 'Analyste supprimé avec succès.');
    }

}

