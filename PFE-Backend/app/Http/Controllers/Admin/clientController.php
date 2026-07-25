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
        $client = User::with('roles')->wherehas('roles',function($query){
            $query->where('name','analyst');
        })->get();

        return view('admin.client.index',compact('client'));
    }

    public function create(request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8|confirmed',
            'company_name' => 'required|string|max:255',
        ]);

        $client= User::create([
        'first_name'         => $validated['first_name'],
        'last_name'          => $validated['last_name'],
        'email'              => $validated['email'],
        'password'           => Hash::make($validated['password']),
        'company_name'       => $validated['company_name'] ?? null,
        'phone'              => $validated['phone'] ?? null,
        'account_status'     => $validated['account_status'] ?? 'pending',
        'activated_at'       => null,
        'wants_newsletter'   => $validated['wants_newsletter'] ?? false,
        'newsletter_category'=> $validated['wants_newsletter'] ?? false
    ]);
        $role = Role::where('name', 'client')->first();
        $client->roles()->attach($role);

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
    }
    public function destroy($id)
    {
        $client = User::findOrFail($id);
        $client->delete();
    }

}

