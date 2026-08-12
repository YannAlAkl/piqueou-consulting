@extends('layouts.admin')

@section('title', 'Gestion des clients')
@section('subtitle', 'Liste des comptes clients du portail.')

@section('actions')
    <a href="{{ route('admin.client.create') }}" class="admin-btn admin-btn-blue">+ Ajouter un client</a>
@endsection

<!-- Lien CDN corrigé pour afficher les icônes Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" type="text/css" href="resources/css/admin.css">

@section('content')

<!-- Légende explicative des pastilles et des actions -->
<div class="admin-legend-box" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
    <h4 style="margin-top: 0; margin-bottom: 10px; font-size: 15px; font-weight: bold; color: #334155;">Légende et guide des statuts :</h4>
    <ul style="margin: 0; padding-left: 20px; color: #475569; font-size: 14px; line-height: 1.6;">
        <li>
            <span class="pastille-verte" style="display: inline-block; width: 10px; height: 10px; background-color: #22c55e; border-radius: 50%; margin-right: 6px;"></span>
            <strong>Actif :</strong> Le compte est validé et pleinement opérationnel. 
            <em>(Action : Aucune requise, le client peut se connecter).</em>
        </li>
        <li>
            <span class="pastille-jaune" style="display: inline-block; width: 10px; height: 10px; background-color: #eab308; border-radius: 50%; margin-right: 6px;"></span>
            <strong>En attente :</strong> Le compte vient d'être créé ou requiert une validation. 
            <em>(Action : Vérifiez les informations et cliquez sur l'icône de validation <i class="fa-solid fa-circle-check text-green-600"></i> pour l'activer).</em>
        </li>
        <li>
            <span class="pastille-rouge" style="display: inline-block; width: 10px; height: 10px; background-color: #ef4444; border-radius: 50%; margin-right: 6px;"></span>
            <strong>Inactif :</strong> Le compte est désactivé, suspendu ou non vérifié. 
            <em>(Action : Vous pouvez le réactiver via le bouton d'activation ou le supprimer si nécessaire).</em>
        </li>
    </ul>
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
                                <span class="admin-legend-item" title="Actif"><span class="pastille-verte"></span></span>
                            @elseif ($client->account_status === 'pending')
                                <span class="admin-legend-item" title="En attente"><span class="pastille-jaune"></span></span>
                            @else
                                <span class="admin-legend-item" title="Inactif"><span class="pastille-rouge"></span></span>
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
                            <!-- Conteneur flex aligné horizontalement pour toutes vos icônes -->
                            <div class="flex items-center gap-3" style="display: flex; gap: 12px; align-items: center;">
                                
                                <!-- Voir -->
                                <a href="{{ route('admin.client.show', $client->id) }}" class="admin-action admin-action-blue" title="Voir">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                
                                <!-- Modifier -->
                                <a href="{{ route('admin.client.edit', $client->id) }}" class="admin-action admin-action-yellow" title="Modifier">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <!-- Activer -->
                                @if ($client->account_status !== 'active')
                                    <form method="POST" action="{{ route('admin.client.verify', $client->id) }}"
                                          onsubmit="return confirm('Activer ce compte client ?');" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="admin-action admin-action-green" title="Activer" style="background: none; border: none; cursor: pointer; padding: 0;">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </button>
                                    </form>
                                @endif

                                <!-- Supprimer -->
                                <form method="POST" action="{{ route('admin.client.destroy', $client->id) }}"
                                      onsubmit="return confirm('Confirmer la suppression ?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-action admin-action-red" title="Supprimer" style="background: none; border: none; cursor: pointer; padding: 0;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
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