@php
    $analystList = $analystes ?? $users ?? collect();
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Analystes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- En-tête avec bouton de création --}}
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Liste des analystes</h3>
                <a href="{{ route('admin.analyst.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + Nouvel analyste
                </a>
            </div>

            {{-- Filtres --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.analyst.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <x-input-label for="search" value="Recherche" />
                        <x-text-input
                            id="search"
                            name="search"
                            type="text"
                            class="mt-1 block w-full min-w-[220px]"
                            :value="$search ?? ''"
                            placeholder="Nom, email, entreprise..."
                        />
                    </div>
                    <div>
                        <x-input-label for="status" value="Statut" />
                        <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Tous</option>
                            <option value="pending" @selected(($statusFilter ?? '') === 'pending')>En attente</option>
                            <option value="active" @selected(($statusFilter ?? '') === 'active')>Actif</option>
                            <option value="inactive" @selected(($statusFilter ?? '') === 'inactive')>Inactif</option>
                        </select>
                    </div>
                    <x-primary-button>Filtrer</x-primary-button>
                    @if(($search ?? '') || ($statusFilter ?? ''))
                        <a href="{{ route('admin.analyst.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                            Réinitialiser
                        </a>
                    @endif
                </form>
            </div>

            {{-- Tableau des analystes --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($analystList->isEmpty())
                        <p class="text-gray-500">Aucun analyste trouvé.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inscrit le</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($analystList as $analyst)
                                        @php
                                            $statusClasses = match($analyst->account_status) {
                                                'active' => 'bg-green-100 text-green-800',
                                                'inactive' => 'bg-red-100 text-red-800',
                                                default => 'bg-yellow-100 text-yellow-800',
                                            };
                                            $statusLabel = match($analyst->account_status) {
                                                'active' => 'Actif',
                                                'inactive' => 'Inactif',
                                                default => 'En attente',
                                            };
                                        @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $analyst->first_name }} {{ $analyst->last_name }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $analyst->email }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $analyst->phone ?? '—' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClasses }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                                {{ $analyst->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                                <div class="flex gap-2">
                                                    <a href="{{ route('admin.analyst.show', $analyst->id) }}" class="text-indigo-600 hover:text-indigo-900">Voir</a>
                                                    <a href="{{ route('admin.analyst.edit', $analyst->id) }}" class="text-yellow-600 hover:text-yellow-900">Modifier</a>
                                                    <form method="POST" action="{{ route('admin.analyst.destroy', $analyst->id) }}" onsubmit="return confirm('Supprimer cet analyste ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($analystList, 'links'))
                            <div class="mt-4">
                                {{ $analystList->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

