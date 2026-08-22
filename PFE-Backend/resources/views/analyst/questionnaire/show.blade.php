@extends('layouts.analyst')

@section('title', 'Analyse du dossier')
@section('subtitle', $soumission->questionnaire->title ?? 'Questionnaire')

@section('content')

    <a href="{{ route('analyst.questionnaire.index') }}" class="analyst-back">
        &larr; Retour à la liste
    </a>

    <div class="analyst-card">
        <h2 class="analyst-card-title">{{ $soumission->user->name ?? 'Client inconnu' }}</h2>

        <p class="text-sm text-gray-600">
            {{ $soumission->user->email ?? '-' }}
            @if ($soumission->user && $soumission->user->company_name)
                — {{ $soumission->user->company_name }}
            @endif
        </p>

        @if ($soumission->submitted_at)
            <p class="analyst-meta">
                Questionnaire envoyé le {{ $soumission->submitted_at->format('d/m/Y à H:i') }}
            </p>
        @endif
    </div>

    @forelse ($soumission->questionnaire->questions as $question)

        @php
            $reponse = $reponses->get($question->id);
            $valeur = $reponse ? $reponse->answer : null;
        @endphp

        <div class="analyst-question">
            <p class="analyst-question-title">
                {{ $question->position }}. {{ $question->question }}
                @if ($question->required)
                    <span class="analyst-required">*</span>
                @endif
            </p>

            @if ($question->description)
                <p class="text-xs text-gray-500 mb-3">{{ $question->description }}</p>
            @endif

            @if ($valeur !== null && $valeur !== '')
                <div class="analyst-answer">{{ $valeur }}</div>
            @else
                <div class="analyst-answer-empty">Pas de réponse enregistrée</div>
            @endif

            @if ($reponse && $reponse->client_comment)
                <div class="analyst-comment">
                    <strong>Commentaire du client</strong>
                    <p>{{ $reponse->client_comment }}</p>
                </div>
            @endif
        </div>

    @empty
        <div class="analyst-card">
            <p>Aucune question associée à ce questionnaire.</p>
        </div>
    @endforelse

    <div class="analyst-card">
        <h2 class="analyst-card-title">Conclusion de l'analyste</h2>

        <form method="POST" action="{{ route('analyst.questionnaire.store', $soumission->id) }}">
            @csrf

            <label for="conclusion" class="analyst-label">Votre analyse et vos recommandations</label>

            <textarea name="conclusion" id="conclusion" rows="6" class="analyst-textarea"
                      placeholder="Saisissez votre conclusion et vos recommandations pour ce client...">{{ old('conclusion', $soumission->conclusion) }}</textarea>

            @error('conclusion')
                <p class="analyst-form-error">{{ $message }}</p>
            @enderror

            <div class="analyst-form-actions">
                <button type="submit" class="analyst-btn analyst-btn-blue">Enregistrer la conclusion</button>
            </div>
        </form>
    </div>

@endsection
