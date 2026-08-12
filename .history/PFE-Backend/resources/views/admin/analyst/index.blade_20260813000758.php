@extends('layouts.admin')

@section('title', 'Gestion des analystes')
@section('subtitle', 'Liste des comptes analystes du portail.')

@section('actions')
    <a href="{{ route('admin.analyst.create') }}" class="admin-btn admin-btn-blue">+ Ajouter un analyste</a>
@endsection

@section('content')

    <div class="admin-table-box">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Email vérifié</th>
                    <th>Téléphone</th>
                    <th>Activé le</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($analystes as $analyst)
                    <tr>
                        <td>{{ $analyst->name }}</td>
                        <td>{{ $analyst->email }}</td>
                        <td>
                            @if ($analyst->account_status === 'active')
                                <span class="admin-badge admin-badge-green">Actif</span>
                            @elseif ($analyst->account_status === 'pending')
                                <span class="admin-badge admin-badge-yellow">En attente</span>
                            @else
                                <span class="admin-badge admin-badge-red">Inactif</span>
                            @endif
                        </td>
                        <td>
                            @if ($analyst->email_verified_at)
                                Oui
                                <span class="block text-xs text-gray-400">{{ $analyst->email_verified_at->format('d/m/Y') }}</span>
                            @else
                                Non
                            @endif
                        </td>
                        <td>{{ $analyst->phone ?? '-' }}</td>
                        <td>{{ $analyst->activated_at ? $analyst->activated_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ $analyst->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.analyst.show', $analyst->id) }}" class="admin-action admin-action-blue">class="fa-solid fa-eye</a>
                                <a href="{{ route('admin.analyst.edit', $analyst->id) }}" class="admin-action admin-action-yellow">Modifier</a>

                                @if ($analyst->account_status !== 'active')
                                    <form method="POST" action="{{ route('admin.analyst.verify', $analyst->id) }}"
                                          onsubmit="return confirm('Activer ce compte analyste ?');">
                                        @csrf
                                        <button type="submit" class="admin-action admin-action-green">Activer</button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.analyst.destroy', $analyst->id) }}"
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
                        <td colspan="8" class="admin-table-empty">Aucun analyste trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($analystes->hasPages())
        <div class="admin-pagination">
            @if ($analystes->onFirstPage())
                <span class="admin-btn admin-btn-gray">Précédent</span>
            @else
                <a href="{{ $analystes->previousPageUrl() }}" class="admin-btn admin-btn-gray">Précédent</a>
            @endif

            <span>Page {{ $analystes->currentPage() }} sur {{ $analystes->lastPage() }}</span>

            @if ($analystes->hasMorePages())
                <a href="{{ $analystes->nextPageUrl() }}" class="admin-btn admin-btn-gray">Suivant</a>
            @else
                <span class="admin-btn admin-btn-gray">Suivant</span>
            @endif
        </div>
    @endif

@endsection
