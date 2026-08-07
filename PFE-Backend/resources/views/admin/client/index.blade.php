@extends('layouts.app')

@section('header')
    <div class="flex flex-col gap-1">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">Gestion des Clients</h2>
        <p class="text-sm text-gray-600">Liste des clients avec leurs informations principales.</p>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="mx-auto max-w-7xl px-4">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Gestion des Clients</h1>
                    <p class="mt-1 text-sm text-slate-500">Cette page ne contient que les comptes clients.</p>
                </div>
                <a href="{{ route('admin.client.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Ajouter
                </a>
            </div>

            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 border border-green-400 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Nom</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Rôle</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Statut</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Email vérifié</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Entreprise</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Téléphone</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Newsletter</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Activé le</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Inscrit le</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($clients as $client)
                            <tr class="hover:bg-gray-50 align-top">
                                <td class="px-6 py-4 text-sm">{{ $client->name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $client->email }}</td>
                                <td class="px-6 py-4 text-sm">Client</td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($client->account_status === 'active')
                                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Actif</span>
                                    @elseif ($client->account_status === 'pending')
                                        <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">En attente</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Inactif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($client->email_verified_at)
                                        <span class="text-emerald-700">Oui</span>
                                        <span class="block text-xs text-slate-400">{{ $client->email_verified_at->format('d/m/Y') }}</span>
                                    @else
                                        <span class="text-rose-700">Non</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $client->company_name ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $client->phone ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($client->wants_newsletter)
                                        Oui
                                        @if($client->newsletter_category)
                                            <span class="block text-xs text-slate-400">{{ $client->newsletter_category }}</span>
                                        @endif
                                    @else
                                        Non
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $client->activated_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $client->created_at->format('d/m/Y H:i') }}</td>
<td class="px-6 py-4 text-center text-sm">
                                    <a href="{{ route('admin.client.show', $client) }}" class="text-blue-600 hover:underline mr-3">Voir</a>
                                    <a href="{{ route('admin.client.edit', $client) }}" class="text-amber-600 hover:underline mr-3">Modifier</a>
                                    @if($client->account_status === 'pending' || $client->account_status === 'inactive')
                                        <form method="POST" action="{{ route('admin.client.verify', $client) }}" class="inline mr-3" onsubmit="return confirm('Activer ce compte et envoyer l\'email de vérification ?');">
                                            @csrf
                                            <button type="submit" class="text-emerald-600 hover:underline">Activer</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.client.destroy', $client) }}" class="inline" onsubmit="return confirm('Confirmer la suppression ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-12 text-center text-gray-500">
                                    Aucun client trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
