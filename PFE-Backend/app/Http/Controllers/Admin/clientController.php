<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class clientController extends Controller
{
   public function index(Request $request)
    {
        $clients = User::with('roles')->wherehas('roles',function($query){
            $query->where('name','client');
        })->get();

        return view('admin.client.index',compact('clients'));
    }

    public function create()
    {
        return view('admin.client.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8|confirmed',
            'company_name' => 'required|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'account_status' => 'nullable|in:pending,active,inactive',
            'wants_newsletter' => 'nullable|boolean',
        ]);

        $client = User::create([
            'first_name'         => $validated['first_name'],
            'last_name'          => $validated['last_name'],
            'email'              => $validated['email'],
            'password'           => Hash::make($validated['password']),
            'company_name'       => $validated['company_name'] ?? null,
            'phone'              => $validated['phone'] ?? null,
            'account_status'     => $validated['account_status'] ?? 'pending',
            'activated_at'       => null,
            'wants_newsletter'   => $validated['wants_newsletter'] ?? false,
            'newsletter_category'=> $validated['wants_newsletter'] ?? false,
        ]);

        $role = Role::where('name', 'client')->first();
        $client->roles()->attach($role);

        return redirect()->route('admin.client.index')->with('success', 'Client créé avec succès.');
    }

    public function show($id)
    {
        $client = User::with('roles')->findOrFail($id);
        return view('admin.client.show', compact('client'));
    }

    public function edit($id)
    {
        $client = User::findOrFail($id);
        return view('admin.client.edit', compact('client'));
    }

   public function uptade(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name'         => 'required|string|max:255',
            'last_name'          => 'required|string|max:255',
            'email'              => 'required|string|email|max:255|unique:users,email,'.$id,
            'password'           => 'required|string|min:8|confirmed',
            'company_name'       => 'required|string|max:255',
            'phone'              => 'nullable|string|max:20',
            'account_status'     => 'nullable|in:pending,active,inactive',
            'wants_newsletter'   => 'nullable|boolean',
            'newsletter_category'=> 'nullable|boolean',
        ]);
        $client = User::findOrFail($id);
        $client->update($validated);

        return redirect()->route('admin.client.index')->with('success', 'Client mis à jour avec succès.');
    }
    public function destroy($id)
    {
        $client = User::findOrFail($id);
        $client->delete();

        return redirect()->route('admin.client.index')->with('success', 'Client supprimé avec succès.');
    }

    /**
     * Activate a client account and send a verification email.
     */
    public function verify($id)
    {
        $client = User::findOrFail($id);

        $client->account_status = 'active';
        $client->activated_at = now();
        $client->save();

        // Envoyer l'email de vérification au client.
        $client->sendEmailVerificationNotification();

        return redirect()->route('admin.client.index')->with('success', 'Compte client activé et email de vérification envoyé.');
    }

}

