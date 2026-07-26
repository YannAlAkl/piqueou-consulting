<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6">Modifier: {{ $analyst->first_name }} {{ $analyst->last_name }}</h1>

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-400 rounded">
                        <strong>Erreurs :</strong>
                        <ul class="list-disc list-inside text-sm mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.analyst.update', $analyst) }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="first_name" class="block text-sm font-semibold mb-2">Prénom *</label>
                            <input type="text" name="first_name" id="first_name"
                                value="{{ old('first_name', $analyst->first_name) }}" required
                                class="w-full px-3 py-2 border rounded @error('first_name') border-red-500 @enderror">
                            @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-semibold mb-2">Nom *</label>
                            <input type="text" name="last_name" id="last_name"
                                value="{{ old('last_name', $analyst->last_name) }}" required
                                class="w-full px-3 py-2 border rounded @error('last_name') border-red-500 @enderror">
                            @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-semibold mb-2">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $analyst->email) }}" required
                            class="w-full px-3 py-2 border rounded @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="phone" class="block text-sm font-semibold mb-2">Téléphone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $analyst->phone) }}"
                                class="w-full px-3 py-2 border rounded">
                        </div>

                        <div>
                            <label for="company_name" class="block text-sm font-semibold mb-2">Entreprise</label>
                            <input type="text" name="company_name" id="company_name"
                                value="{{ old('company_name', $analyst->company_name) }}"
                                class="w-full px-3 py-2 border rounded">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="account_status" class="block text-sm font-semibold mb-2">Statut *</label>
                        <select name="account_status" id="account_status" required
                            class="w-full px-3 py-2 border rounded @error('account_status') border-red-500 @enderror">
                            <option value="active" {{ old('account_status', $analyst->account_status) === 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="pending" {{ old('account_status', $analyst->account_status) === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="inactive" {{ old('account_status', $analyst->account_status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('account_status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Mettre à jour
                        </button>
                        <a href="{{ route('admin.analyst.index') }}"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>