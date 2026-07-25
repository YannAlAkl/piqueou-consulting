@php
    $client = $client ?? $user ?? null;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails du Client') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($client)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations personnelles</h3>
                                <dl class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Prénom</dt>
                                        <dd class="text-sm text-gray-900">{{ $client->first_name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Nom</dt>
                                        <dd class="text-sm text-gray-900">{{ $client->last_name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="text-sm text-gray-900">{{ $client->email }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                                        <dd class="text-sm text-gray-900">{{ $client->phone ?? 'Non renseigné' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Entreprise</dt>
                                        <dd class="text-sm text-gray-900">{{ $client->company_name ?? 'Non renseignée' }}</dd>
                                    </div>
                                </dl>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Statut du compte</h3>
                                <dl class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                        <dd class="text-sm">
                                            @php
                                                $statusClasses = match($client->account_status) {
                                                    'active' => 'bg-green-100 text-green-800',
                                                    'inactive' => 'bg-red-100 text-red-800',
                                                    default => 'bg-yellow-100 text-yellow-800',
                                                };
                                                $statusLabel = match($client->account_status) {
                                                    'active' => 'Actif',
                                                    'inactive' => 'Inactif',
                                                    default => 'En attente',
                                                };
                                            @endphp
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClasses }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email vérifié</dt>
                                        <dd class="text-sm text-gray-900">
                                            @if($client->email_verified_at)
                                                <span class="text-green-600">Oui</span>
                                                <span class="text-xs text-gray-400 block">{{ $client->email_verified_at->format('d/m/Y H:i') }}</span>
                                            @else
                                                <span class="text-red-600">Non</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Newsletter</dt>
                                        <dd class="text-sm text-gray-900">
                                            @if($client->wants_newsletter)
                                                Oui
                                                @if($client->newsletter_category)
                                                    <span class="text-xs text-gray-400 block">{{ $client->newsletter_category }}</span>
                                                @endif
                                            @else
                                                Non
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Inscrit le</dt>
                                        <dd class="text-sm text-gray-900">{{ $client->created_at->format('d/m/Y H:i') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Activé le</dt>
                                        <dd class="text-sm text-gray-900">{{ $client->activated_at?->format('d/m/Y H:i') ?? '—' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-4">
                            <a href="{{ route('admin.client.edit', $client->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Modifier
                            </a>
                            <a href="{{ route('admin.client.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Retour à la liste
                            </a>
                        </div>
                    @else
                        <p class="text-gray-500">Client introuvable.</p>
                        <a href="{{ route('admin.client.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                            Retour à la liste
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

