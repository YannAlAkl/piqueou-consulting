<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Statistiques --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <p class="text-sm text-gray-500">Clients</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['clients'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <p class="text-sm text-gray-500">Analystes</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['analysts'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <p class="text-sm text-gray-500">En attente</p>
                    <p class="text-2xl font-semibold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <p class="text-sm text-gray-500">Actifs</p>
                    <p class="text-2xl font-semibold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <p class="text-sm text-gray-500">Inactifs</p>
                    <p class="text-2xl font-semibold text-red-600">{{ $stats['inactive'] }}</p>
                </div>
            </div>

            {{-- Filtres --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <x-input-label for="search" value="Recherche" />
                        <x-text-input
                            id="search"
                            name="search"
                            type="text"
                            class="mt-1 block w-full min-w-[220px]"
                            :value="$search"
                            placeholder="Nom, email, entreprise..."
                        />
                    </div>
                    <div>
                        <x-input-label for="role" value="Rôle" />
                        <select id="role" name="role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Tous</option>
                            <option value="client" @selected($roleFilter === 'client')>Client</option>
                            <option value="analyst" @selected($roleFilter === 'analyst')>Analyste</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="status" value="Statut" />
                        <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Tous</option>
                            <option value="pending" @selected($statusFilter === 'pending')>En attente</option>
                            <option value="active" @selected($statusFilter === 'active')>Actif</option>
                            <option value="inactive" @selected($statusFilter === 'inactive')>Inactif</option>
                        </select>
                    </div>
                    <x-primary-button>Filtrer</x-primary-button>
                    @if($search || $roleFilter || $statusFilter)
                        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                            Réinitialiser
                        </a>
                    @endif
                </form>
            </div>

            {{-- Tableau des comptes --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Comptes clients et analystes</h3>

                    @if($users->isEmpty())
                        <p class="text-gray-500">Aucun compte trouvé.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email vérifié</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entreprise</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Newsletter</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activé le</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inscrit le</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($users as $user)
                                        @php
                                            $roleName = $user->role()?->name;
                                            $statusClasses = match($user->account_status) {
                                                'active' => 'bg-green-100 text-green-800',
                                                'inactive' => 'bg-red-100 text-red-800',
                                                default => 'bg-yellow-100 text-yellow-800',
                                            };
                                            $statusLabel = match($user->account_status) {
                                                'active' => 'Actif',
                                                'inactive' => 'Inactif',
                                                default => 'En attente',
                                            };
                                            $roleLabel = match($roleName) {
                                                'analyst' => 'Analyste',
                                                'client' => 'Client',
                                                default => ucfirst($roleName ?? '—'),
                                            };
                                        @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $user->email }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                    {{ $roleLabel }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClasses }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                                @if($user->email_verified_at)
                                                    <span class="text-green-600">Oui</span>
                                                    <span class="text-xs text-gray-400 block">{{ $user->email_verified_at->format('d/m/Y') }}</span>
                                                @else
                                                    <span class="text-red-600">Non</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $user->company_name ?? '—' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $user->phone ?? '—' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                                @if($user->wants_newsletter)
                                                    Oui
                                                    @if($user->newsletter_category)
                                                        <span class="text-xs text-gray-400 block">{{ $user->newsletter_category }}</span>
                                                    @endif
                                                @else
                                                    Non
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                                {{ $user->activated_at?->format('d/m/Y H:i') ?? '—' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                                {{ $user->created_at->format('d/m/Y H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
