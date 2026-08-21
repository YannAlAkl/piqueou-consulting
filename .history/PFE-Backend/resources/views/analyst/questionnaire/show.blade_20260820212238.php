@extends('layouts.client')

@section('title', 'Questionnaires à analyser')
@section('subtitle', 'Les soumissions clients qui vous sont assignées.')

@section('content')

    @if(session('success'))
        <div class="client-badge client-badge-green mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="client-badge client-badge-yellow mb-4">{{ session('error') }}</div>
    @endif

    @forelse ($questionnaires as $soumission)

        <div class="client-card mb-6">
            <h2 class="client-card-title">
                {{ $soumission->questionnaire->title ?? 'Questionnaire' }}
            </h2>
            <p class="text-sm text-gray-600">
                Client : {{ $soumission->user->name ?? 'Inconnu' }}
            </p>

            @foreach ($soumission->questionnaire->questions as $question)
                <div class="client-question">
                    <p class="client-question-title">
                        {{ $question->position }}. {{ $question->question }}
                        @if ($question->required)<span class="client-required">*</span>@endif
                    </p>

                    @if ($question->description)
                        <p class="text-xs text-gray-500 mb-3">{{ $question->description }}</p>
                    @endif
                </div>
            @endforeach

            @if ($soumission->conclusion)
                <div class="client-card mt-6">
                    <h3 class="client-card-title">Conclusion de l'analyste</h3>
                    <p class="text-sm text-gray-700">{{ $soumission->conclusion }}</p>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('analyst.questionnaire.show', $soumission->id) }}"
                   class="client-btn client-btn-blue">
                    Analyser
                </a>
            </div>
        </div>

    @empty
        <div class="client-card">
            <p>Aucune soumission à analyser pour le moment.</p>
        </div>
    @endforelse

@endsection
