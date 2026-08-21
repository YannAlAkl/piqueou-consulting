@extends('layouts.client')

@section('title', $questionnaires->title)
@section('subtitle', $modifiable ? 'Répondez aux questions puis envoyez.' : 'Vos réponses ont été envoyées.')

@section('content')

    @if(session('success'))
        <div class="client-badge client-badge-green mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="client-badge client-badge-yellow mb-4">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('client.questionnaire.submit', $questionnaire->id) }}">
        @csrf

        @foreach ($questionnaire->questions as $question)
            @php
                $reponse = $reponses->get($question->id);
                $valeur = $reponse ? $reponse->answer : '';
                $commentaire = $reponse ? $reponse->client_comment : '';
                $choisies = $valeur ? explode(', ', $valeur) : [];
            @endphp

            <div class="client-question">
                <p class="client-question-title">
                    {{ $question->position }}. {{ $question->question }}
                    @if ($question->required)<span class="client-required">*</span>@endif
                </p>

                @if ($question->description)
                    <p class="text-xs text-gray-500 mb-3">{{ $question->description }}</p>
                @endif

                @if ($question->type->name === 'unique_choice')
                    @foreach ($question->options as $option)
                        <label class="client-option">
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}"
                                   {{ $valeur === $option ? 'checked' : '' }} {{ $modifiable ? '' : 'disabled' }}>
                            {{ $option }}
                        </label>
                    @endforeach

                @elseif ($question->type->name === 'multiple_choice')
                    @foreach ($question->options as $option)
                        <label class="client-option">
                            <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option }}"
                                   {{ in_array($option, $choisies) ? 'checked' : '' }} {{ $modifiable ? '' : 'disabled' }}>
                            {{ $option }}
                        </label>
                    @endforeach

                @else
                    <textarea name="answers[{{ $question->id }}]" rows="4" class="client-textarea" {{ $modifiable ? '' : 'disabled' }}>{{ $valeur }}</textarea>
                @endif

                <label class="client-label">Commentaire (optionnel)</label>
                <textarea name="comments[{{ $question->id }}]" rows="2" class="client-textarea" {{ $modifiable ? '' : 'disabled' }}>{{ $commentaire }}</textarea>
            </div>
        @endforeach

        @if ($modifiable)
            <div class="client-form-actions">
                <button type="submit" name="action" value="enregistrer" class="client-btn client-btn-gray">Enregistrer</button>
                <button type="submit" name="action" value="envoyer" class="client-btn client-btn-blue"
                        onclick="return confirm('Une fois envoyé, vous ne pourrez plus modifier. Continuer ?');">
                    Envoyer
                </button>
            </div>
        @endif
    </form>

    @if ($soumission->conclusion)
        <div class="client-card mt-6">
            <h2 class="client-card-title">Conclusion de l'analyste</h2>
            <p class="text-sm text-gray-700">{{ $soumission->conclusion }}</p>
        </div>
    @endif

@endsection
