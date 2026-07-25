<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AnalaystController extends Controller
{
   public function index(Request $request)
    {
        $analystes = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'analyst');
        })->get();

        return view('admin.analyst.index', compact('analystes'));
    }

    public function create(request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8|confirmed',
        ]);

        $analyst = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
        ]);

        $role = Role::where('name', 'analyst')->first();
        $analyst->roles()->attach($role);
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
    }
    public function destroy($id)
    {
        $analyst = User::findOrFail($id);
        $analyst->delete();
    }

}

