@extends('layouts.app') {{-- Remplacez par votre layout principal --}}

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">

    <!-- Bouton Retour -->
    <a href="{{ route('analyst.questionnaire.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-6">
        &larr; Retour à la liste
    </a>

    <!-- En-tête Client & Questionnaire -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            Analyse : {{ $soumission->questionnaire->title ?? 'Questionnaire' }}
        </h1>
        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
            <p><strong>Client :</strong> {{ $soumission->user->name }} ({{ $soumission->user->email }})</p>
            <p><strong>Soumis le :</strong> {{ $soumission->created_at->format('d/m/Y à H:i') }}</p>
        </div>
    </div>

    <!-- Réponses du Client -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Réponses du client</h2>

        @if($soumission->questionnaire && $soumission->questionnaire->questions->isNotEmpty())
            <div class="space-y-6">
                @foreach($soumission->questionnaire->questions as $index => $question)
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="font-medium text-gray-900 mb-2">
                            {{ $index + 1 }}. {{ $question->label }}
                        </p>

                        {{-- Affichage de la réponse selon votre structure pivot / relation --}}
                        <div class="text-gray-700 bg-white p-3 rounded border border-gray-200">
                            @php
                                // Ajustez selon le nom de votre relation/colonne de réponse dans le modèle
                                $reponse = $question->answers->where('user_questionnaire_id', $soumission->id)->first();
                            @endphp

                            {{ $reponse->value ?? 'Pas de réponse enregistrée' }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Aucune question associée disponible.</p>
        @endif
    </div>

    <!-- Formulaire d'ajout de recommandation -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Ajouter une recommandation</h2>

        <form action="{{ route('analyst.questionnaire.store', $soumission->id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="recommendation" class="block text-sm font-medium text-gray-700 mb-2">
                    Recommandations de l'analyste
                </label>
                <textarea
                    name="recommendation"
                    id="recommendation"
                    rows="5"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3 border"
                    placeholder="Saisissez vos conseils et recommandations pour ce client..."
                >{{ old('recommendation', $soumission->recommendation) }}</textarea>
                @error('recommendation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-md shadow transition duration-150"
                >
                    Enregistrer et passer en revue
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
