<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="bg-blue-600 text-white p-6">
                    <h1 class="text-2xl font-bold">{{ $analyst->first_name }} {{ $analyst->last_name }}</h1>
                    <p class="text-blue-100">{{ $analyst->email }}</p>
                </div>

                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-600">Statut:</label>
                        <span class="px-3 py-1 text-sm rounded inline-block mt-1
                            @if ($analyst->account_status === 'active') bg-green-100 text-green-800
                            @elseif ($analyst->account_status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $analyst->account_status === 'active' ? 'Actif' : ($analyst->account_status === 'pending' ? 'En attente' : 'Inactif') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600">Email</label>
                            <p class="text-gray-900 mt-1">{{ $analyst->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600">Téléphone</label>
                            <p class="text-gray-900 mt-1">{{ $analyst->phone ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600">Entreprise</label>
                            <p class="text-gray-900 mt-1">{{ $analyst->company_name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600">Inscrit le</label>
                            <p class="text-gray-900 mt-1">{{ $analyst->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4 border-t">
                        <a href="{{ route('admin.analyst.edit', $analyst) }}"
                            class="px-4 py-2 bg-amber-600 text-white rounded hover:bg-amber-700">
                            Modifier
                        </a>
                        <form method="POST" action="{{ route('admin.analyst.destroy', $analyst) }}"
                            style="display:inline;" onsubmit="return confirm('Confirmer ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Supprimer
                            </button>
                        </form>
                        <a href="{{ route('admin.analyst.index') }}"
                            class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                            Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>