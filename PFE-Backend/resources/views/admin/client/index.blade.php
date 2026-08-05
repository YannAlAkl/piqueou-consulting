<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Clients</title>
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>

    <h1>Gestion des Clients</h1>

    <div>
        <a href="/admin/clients/create" class="btn-add">+ Nouveau client</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Entreprise</th>
                <th>Téléphone</th>
                <th>Statut</th>
                <th>Inscrit le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($clients as $client)
            <tr>
                <td>{{ $client->first_name }} {{ $client->last_name }}</td>
                <td>{{ $client->email }}</td>
                <td>{{ $client->company_name ?? '—' }}</td>
                <td>{{ $client->phone ?? '—' }}</td>
                <td>
                    @if($client->account_status === 'active')
                        <span class="status-active">Actif</span>
                    @elseif($client->account_status === 'inactive')
                        <span class="status-inactive">Inactif</span>
                    @else
                        <span class="status-pending">En attente</span>
                    @endif
                </td>
                <td>{{ $client->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="/admin/clients/{{ $client->id }}" class="action-link">Voir</a>
                    <a href="/admin/clients/{{ $client->id }}/edit" class="action-edit">Modifier</a>
                    <form method="POST" action="/admin/clients/{{ $client->id }}" onsubmit="return confirm('Supprimer ce client ?')" class="inline-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Supprimer</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">Aucun client trouvé.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @endif

</body>
</html>
