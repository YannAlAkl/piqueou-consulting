@extends('layouts.admin')

@section('title', 'Gestion des clients')
@section('subtitle', 'Liste des comptes clients du portail.')

@section('actions')
    <a href="{{ route('admin.client.create') }}" class="admin-btn admin-btn-blue">+ Ajouter un client</a>
@endsection

<link rel="stylesheet" type="text/css" href="resources/css/admin.css">

@section('content')

<div class="admin-legend">
    <span class="admin-legend-item">
        <span class="pastille-verte"></span>
        Actif
    </span>
    <span class="admin-legend-item">
        <span class="pastille-jaune"></span>
        En attente
    </span>
    <span class="admin-legend-item">
        <span class="pastille-rouge"></span>
        Inactif
    </span>
</div>
    <div class="admin-table-box">

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Email vérifié</th>
                    <th>Entreprise</th>
                    <th>Téléphone</th>
                    <th>Newsletter</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($clients as $client)
                    <tr>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>
                        @if ($client->account_status === 'active')
                        <span class="admin-badge admin-badge-green"><span class="admin-badge-dot"></span>Actif</span>
                        @elseif ($client->account_status === 'pending')
                        <span class="admin-badge admin-badge-yellow"><span class="admin-badge-dot"></span>En attente</span>
                        @else
                        <span class="admin-badge admin-badge-red"><span class="admin-badge-dot"></span>Inactif</span>
                        @endif
                        </td>
                        <td>
                            @if ($client->email_verified_at)
                                Oui
                                <span class="block text-xs text-gray-400">{{ $client->email_verified_at->format('d/m/Y') }}</span>
                            @else
                                Non
                            @endif
                        </td>
                        <td>{{ $client->company_name ?? '-' }}</td>
                        <td>{{ $client->phone ?? '-' }}</td>
                        <td>
                            @if ($client->wants_newsletter)
                                Oui
                                @if ($client->newsletter_category)
                                    <span class="block text-xs text-gray-400">{{ $client->newsletter_category }}</span>
                                @endif
                            @else
                                Non
                            @endif
                        </td>
                        <td>{{ $client->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.client.show', $client->id) }}" class="admin-action admin-action-blue">Voir</a>
                                <a href="{{ route('admin.client.edit', $client->id) }}" class="admin-action admin-action-yellow">Modifier</a>

                                @if ($client->account_status !== 'active')
                                    <form method="POST" action="{{ route('admin.client.verify', $client->id) }}"
                                          onsubmit="return confirm('Activer ce compte client ?');">
                                        @csrf
                                        <button type="submit" class="admin-action admin-action-green">Activer</button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.client.destroy', $client->id) }}"
                                      onsubmit="return confirm('Confirmer la suppression ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-action admin-action-red">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="admin-table-empty">Aucun client trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($clients->hasPages())
        <div class="admin-pagination">
            @if ($clients->onFirstPage())
                <span class="admin-btn admin-btn-gray">Précédent</span>
            @else
                <a href="{{ $clients->previousPageUrl() }}" class="admin-btn admin-btn-gray">Précédent</a>
            @endif

            <span>Page {{ $clients->currentPage() }} sur {{ $clients->lastPage() }}</span>

            @if ($clients->hasMorePages())
                <a href="{{ $clients->nextPageUrl() }}" class="admin-btn admin-btn-gray">Suivant</a>
            @else
                <span class="admin-btn admin-btn-gray">Suivant</span>
            @endif
        </div>
    @endif

@endsection
