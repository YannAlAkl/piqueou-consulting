<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Questionnaire client</title>

</head>

<body>

    <h1>Questionnaires</h1>

    @if (session('success'))
        <div style="padding: 1rem; background: #d4edda; color: #155724; margin-bottom: 1rem; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    @if (isset($questionnaire))

        <div style="margin-bottom: 2rem; padding: 1rem; border: 1px solid #ddd; border-radius: 8px;">
            <h2>{{ $questionnaire->title }}</h2>
            <p>{{ $questionnaire->description }}</p>
        </div>

        <form method="POST" action="{{ route('client.questionnaire.submit', $questionnaire->id) }}">
            @csrf

            @foreach ($questionnaire->questions as $question)
                <div style="margin-bottom: 1.5rem; padding: 1rem; border: 1px solid #eee; border-radius: 8px;">
                    <p><strong>{{ $question->question }}</strong></p>

                    @if ($question->description)
                        <p>{{ $question->description }}</p>
                    @endif

                    @if ($question->question_type_id == 1)
                        <input type="text" name="answers[{{ $question->id }}]" @if ($question->required) required @endif style="width:100%; padding:.5rem;" />
                    @elseif ($question->question_type_id == 2)
                        <label><input type="radio" name="answers[{{ $question->id }}]" value="oui" @if ($question->required) required @endif /> Oui</label>
                        <br>
                        <label><input type="radio" name="answers[{{ $question->id }}]" value="non" /> Non</label>
                    @elseif ($question->question_type_id == 3)
                        @php
                            $options = $question->options;
                            if (is_string($options)) {
                                $options = json_decode($options, true);
                            }
                        @endphp
                        @if (is_array($options))
                            @foreach ($options as $option)
                                @php
                                    $optionId = is_array($option) ? ($option['id'] ?? $option['name'] ?? $option['option'] ?? '') : $option;
                                    $optionName = is_array($option) ? ($option['name'] ?? $option['option'] ?? $optionId) : $option;
                                @endphp
                                <label>
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $optionId }}" @if ($question->required) required @endif /> {{ $optionName }}
                                </label>
                                <br>
                            @endforeach
                        @endif
                    @elseif ($question->question_type_id == 4)
                        <textarea name="answers[{{ $question->id }}]" rows="5" @if ($question->required) required @endif style="width:100%; padding:.5rem;"></textarea>
                    @endif
                </div>
            @endforeach

            <button type="submit" style="padding: 0.75rem 1.5rem; background: #007bff; color: white; border:none; border-radius: 4px; cursor:pointer;">Envoyer le questionnaire</button>
        </form>

    @elseif (isset($questionnaires) && $questionnaires->count())

        <div style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
            @foreach ($questionnaires as $questionnaire)
                <a href="{{ route('client.questionnaire.show', $questionnaire->id) }}" style="display:block; padding: 1.25rem; text-decoration: none; background: #f8f9fa; border: 1px solid #ddd; border-radius: 12px; color: inherit; transition: transform .15s ease, box-shadow .15s ease;">
                    <h2>{{ $questionnaire->title }}</h2>
                    <p>{{ $questionnaire->description }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($questionnaire->status) }}</p>
                </a>
            @endforeach
        </div>

    @else

        <p>Aucun questionnaire disponible pour le moment.</p>

    @endif

</body>

</html>

