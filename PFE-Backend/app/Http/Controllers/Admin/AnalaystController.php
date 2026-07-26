<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnalaystController extends Controller
{
    public function index()
    {
        $analystes = User::whereHas('roles', fn($q) => $q->where('name', 'analyst'))->paginate(10);
        return view('admin.analyst.index', ['analystes' => $analystes]);
    }

    public function create()
    {
        return view('admin.analyst.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'account_status' => 'nullable|in:pending,active,inactive',
        ]);

        $analyst = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'company_name' => $validated['company_name'],
            'account_status' => $validated['account_status'] ?? 'pending',
        ]);

        $analyst->roles()->attach(Role::where('name', 'analyst')->first());
        return redirect()->route('admin.analyst.index')->with('success', 'Analyste ajouté.');
    }

    public function show($id)
    {
        $analyst = User::findOrFail($id);
        return view('admin.analyst.show', ['analyst' => $analyst]);
    }

    public function edit($id)
    {
        $analyst = User::findOrFail($id);
        return view('admin.analyst.edit', ['analyst' => $analyst]);
    }

    public function uptade(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'account_status' => 'nullable|in:pending,active,inactive',
        ]);

        $analyst = User::findOrFail($id);
        $analyst->update($validated);
        return redirect()->route('admin.analyst.index')->with('success', 'Analyste mis à jour.');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.analyst.index')->with('success', 'Analyste supprimé.');
    }
}

