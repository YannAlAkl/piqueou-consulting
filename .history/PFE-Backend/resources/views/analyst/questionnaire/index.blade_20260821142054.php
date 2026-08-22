@extends('layouts.client')

@section('title', 'Questionnaires à analyser')
@section('subtitle', 'Les soumissions clients qui vous sont assignées.')

@section('content')

    @forelse ($questionnaires as $soumission)

        <div class="client-card mb-6">
            <h2 class="client-card-title">
                {{ $soumission->questionnaire->title ?? 'Questionnaire' }}
            </h2>

            <p class="text-sm text-gray-600">
                Client : {{ $soumission->user->name ?? 'Inconnu' }}
            </p>

            <p class="mt-4 text-sm">
                Statut :
                @if ($soumission->status === 'submitted')
                    <span class="client-badge client-badge-yellow">À analyser</span>
                @elseif ($soumission->status === 'under_review')
                    <span class="client-badge client-badge-blue">En cours d'analyse</span>
                @else
                    <span class="client-badge client-badge-gray">{{ $soumission->status }}</span>
                @endif
            </p>

            @if ($soumission->submitted_at)
                <p class="text-xs text-gray-500 mt-1">
                    Envoyé le {{ $soumission->submitted_at->format('d/m/Y H:i') }}
                </p>
            @endif

            <div class="mt-4 flex gap-2">
                <a href="{{ route('analyst.questionnaire.show', $soumission->id) }}"
                   class="client-btn client-btn-gray">
                    Voir le questionnaire
                </a>
                <a href="{{ route('analyst.questionnaire.show', $soumission->id) }}"
                   class="client-btn client-btn-blue">
                    Analyser
                </a>
                <a href="{{ route('client.questionnaire.') }}"
            </div>
        </div>

    @empty
        <div class="client-card">
            <p>Aucune soumission à analyser pour le moment.</p>
        </div>
    @endforelse

@endsection
