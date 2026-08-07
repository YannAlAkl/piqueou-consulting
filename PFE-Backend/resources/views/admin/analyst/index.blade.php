@extends('layouts.app')

@section('header')
    <div class="flex flex-col gap-1">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">Gestion des Analystes</h2>
        <p class="text-sm text-gray-600">Liste des analystes avec leurs informations principales.</p>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Gestion des Analystes</h1>
                    <p class="mt-1 text-sm text-slate-500">Cette page regroupe uniquement les comptes analystes.</p>
                </div>
                <a href="{{ route('admin.analyst.create') }}" class="inline-flex items-center rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                    + Ajouter
                </a>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
                <table class="w-full border-separate border-spacing-0 text-sm">
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
                        @forelse ($analystes as $analyst)
                            <tr class="hover:bg-gray-50 align-top">
                                <td class="px-6 py-4 text-sm">{{ $analyst->name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $analyst->email }}</td>
                                <td class="px-6 py-4 text-sm">Analyste</td>
                                <td class="px-6 py-4 text-sm">
                                    <span @class([
                                        'px-2 py-1 text-xs rounded',
                                        'bg-green-100 text-green-800' => $analyst->account_status === 'active',
                                        'bg-yellow-100 text-yellow-800' => $analyst->account_status === 'pending',
                                        'bg-red-100 text-red-800' => !in_array($analyst->account_status, ['active', 'pending']),
                                    ])>
                                        {{ $analyst->account_status === 'active' ? 'Actif' : ($analyst->account_status === 'pending' ? 'En attente' : 'Inactif') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($analyst->email_verified_at)
                                        <span class="text-emerald-700">Oui</span>
                                        <span class="block text-xs text-slate-400">{{ $analyst->email_verified_at->format('d/m/Y') }}</span>
                                    @else
                                        <span class="text-rose-700">Non</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $analyst->company_name ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $analyst->phone ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($analyst->wants_newsletter)
                                        Oui
                                        @if($analyst->newsletter_category)
                                            <span class="block text-xs text-slate-400">{{ $analyst->newsletter_category }}</span>
                                        @endif
                                    @else
                                        Non
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $analyst->activated_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $analyst->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <a href="{{ route('admin.analyst.show', $analyst) }}" class="text-blue-600 hover:underline mr-3">Voir</a>
                                    <a href="{{ route('admin.analyst.edit', $analyst) }}" class="text-amber-600 hover:underline mr-3">Modifier</a>
                                    @if($analyst->account_status === 'pending' || $analyst->account_status === 'inactive')
                                        <form method="POST" action="{{ route('admin.analyst.verify', $analyst) }}" class="inline mr-3" onsubmit="return confirm('Activer ce compte et envoyer l\'email de vérification ?');">
                                            @csrf
                                            <button type="submit" class="text-emerald-600 hover:underline">Activer</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.analyst.destroy', $analyst) }}" class="inline" onsubmit="return confirm('Confirmer la suppression ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-12 text-center text-slate-500">
                                    Aucun analyste trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $analystes->links() }}
            </div>
        </div>
    </div>
@endsection
