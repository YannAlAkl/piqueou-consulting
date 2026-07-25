<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Analystes</title>
</head>
<body>

    <h1>Gestion des Analystes</h1>

    <div>
        <a href="/admin/analysts/create">+ Nouvel analyste</a>
    </div>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Statut</th>
                <th>Inscrit le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($analystes as $analyst)
            <tr>
                <td>{{ $analyst->first_name }} {{ $analyst->last_name }}</td>
                <td>{{ $analyst->email }}</td>
                <td>{{ $analyst->phone ?? '—' }}</td>
                <td>
                    @if($analyst->account_status === 'active')
                        Actif
                    @elseif($analyst->account_status === 'inactive')
                        Inactif
                    @else
                        En attente
                    @endif
                </td>
                <td>{{ $analyst->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="/admin/analysts/{{ $analyst->id }}">Voir</a>
                    <a href="/admin/analysts/{{ $analyst->id }}/edit">Modifier</a>
                    <form method="POST" action="/admin/analysts/{{ $analyst->id }}" style="display:inline;" onsubmit="return confirm('Supprimer cet analyste ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">Aucun analyste trouvé.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if(method_exists($analystes, 'links'))
        <div style="margin-top:20px;">
            {{ $analystes->links() }}
        </div>
    @endif

</body>
</html>
