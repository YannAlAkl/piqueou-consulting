@extends('layouts.client')

@section('title', 'Analyse : ' . ($soumission->questionnaire->title ?? 'Questionnaire'))
@section('subtitle', 'Réponses du client : ' . ($soumission->user->name ?? 'Inconnu'))

@section('content')

    <a href="{{ route('analyst.questionnaire.index') }}" class="client-nav-link">
        &larr; Retour à la liste
    </a>

    <div class="client-card mt-4 mb-6">
        <p class="text-sm text-gray-600">
            Client : {{ $soumission->user->name ?? 'Inconnu' }}
            @if ($soumission->user)({{ $soumission->user->email }})@endif
        </p>
        @if ($soumission->submitted_at)
            <p class="text-xs text-gray-500 mt-1">
                Soumis le {{ $soumission->submitted_at->format('d/m/Y à H:i') }}
            </p>
        @endif
    </div>

    {{-- Réponses du client, question par question --}}
    @forelse ($soumission->questionnaire->questions as $question)

        @php
            // Les réponses ne sont pas préchargées : on récupère celle de ce client
            // pour cette question via la relation answers (question_id + user_id).
            $reponse = $question->answers
                ->where('user_id', $soumission->user_id)
                ->first();
            $valeur = $reponse ? $reponse->answer : null;
        @endphp

        <div class="client-question">
            <p class="client-question-title">
                {{ $question->position }}. {{ $question->question }}
                @if ($question->required)<span class="client-required">*</span>@endif
            </p>
@if($isAnalyst)
    <a href="{{ route('analyst.questionnaire.index') }}"
       class="text-sm font-medium {{ request()->routeIs('analyst.questionnaire.*') ? 'text-blue-700' : 'text-gray-500 hover:text-gray-800' }}">
        Questionnaires à analyser
    </a>
@endif
            @if ($question->description)
                <p class="text-xs text-gray-500 mb-3">{{ $question->description }}</p>
            @endif

            <div class="client-textarea">
                {{ $valeur !== null && $valeur !== '' ? $valeur : 'Pas de réponse enregistrée' }}
            </div>

            @if ($reponse && $reponse->client_comment)
                <p class="text-xs text-gray-500 mt-2">
                    Commentaire du client : {{ $reponse->client_comment }}
                </p>
            @endif
        </div>

    @empty
        <div class="client-card">
            <p>Aucune question associée à ce questionnaire.</p>
        </div>
    @endforelse

    {{-- Conclusion / recommandation de l'analyste --}}
    <div class="client-card mt-6">
        <h2 class="client-card-title">Conclusion de l'analyste</h2>

        <form method="POST" action="{{ route('analyst.questionnaire.store', $soumission->id) }}">
            @csrf

            <textarea
                name="conclusion"
                rows="5"
                class="client-textarea"
                placeholder="Saisissez votre conclusion et vos recommandations pour ce client..."
            >{{ old('conclusion', $soumission->conclusion) }}</textarea>

            @error('conclusion')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            <div class="client-form-actions">
                <button type="submit" class="client-btn client-btn-blue">
                    Enregistrer et passer en revue
                </button>
            </div>
        </form>
    </div>

@endsection
