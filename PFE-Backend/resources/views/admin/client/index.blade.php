<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Clients</title>
</head>
<body>

    <h1>Gestion des Clients</h1>

    <div>
        <a href="/admin/clients/create">+ Nouveau client</a>
    </div>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
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
                        Actif
                    @elseif($client->account_status === 'inactive')
                        Inactif
                    @else
                        En attente
                    @endif
                </td>
                <td>{{ $client->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="/admin/clients/{{ $client->id }}">Voir</a>
                    <a href="/admin/clients/{{ $client->id }}/edit">Modifier</a>
                    <form method="POST" action="/admin/clients/{{ $client->id }}" style="display:inline;" onsubmit="return confirm('Supprimer ce client ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Supprimer</button>
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

    @if(method_exists($clients, 'links'))
        <div style="margin-top:20px;">
            {{ $clients->links() }}
        </div>
    @endif

</body>
</html>
