<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des Clients</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Gestion des Clients</h1>
                <a href="{{ route('admin.client.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
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
                            <th class="px-6 py-3 text-left text-sm font-semibold">Téléphone</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Entreprise</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Statut</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($clients as $client)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">{{ $client->first_name }} {{ $client->last_name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $client->email }}</td>
                                <td class="px-6 py-4 text-sm">{{ $client->phone ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $client->company_name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 text-xs rounded
                                            @if ($client->account_status === 'active') bg-green-100 text-green-800
                                            @elseif ($client->account_status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                        {{ $client->account_status === 'active' ? 'Actif' : ($client->account_status === 'pending' ? 'En attente' : 'Inactif') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <a href="{{ route('admin.client.show', $client) }}"
                                        class="text-blue-600 hover:underline mr-3">Voir</a>
                                    <a href="{{ route('admin.client.edit', $client) }}"
                                        class="text-amber-600 hover:underline mr-3">Modifier</a>
                                    <form method="POST" action="{{ route('admin.client.destroy', $client) }}"
                                        style="display:inline;" onsubmit="return confirm('Confirmer la suppression ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    Aucun client trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
