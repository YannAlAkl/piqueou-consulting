<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$questionnaire->title</title>
</head>
<body>

    <div class="client-badge client-badge-green mb-4">session('success')</div>
    <div class="client-badge client-badge-yellow mb-4">session('error')</div>

    <form method="POST" action="route('client.questionnaire.submit', $questionnaire->id)">

        <div class="client-question">
            <p class="client-question-title">
                $question->position. $question->question
                <span class="client-required">*</span>
            </p>

            <p class="text-xs text-gray-500 mb-3">$question->description</p>

            <!-- unique_choice -->
            <label class="client-option">
                <input type="radio" name="answers[$question->id]" value="$option" checked>
                $option
            </label>

            <!-- multiple_choice -->
            <label class="client-option">
                <input type="checkbox" name="answers[$question->id][]" value="$option" checked>
                $option
            </label>

            <!-- texte libre -->
            <textarea name="answers[$question->id]" rows="4" class="client-textarea">$valeur</textarea>

            <label class="client-label">Commentaire (optionnel)</label>
            <textarea name="comments[$question->id]" rows="2" class="client-textarea">$commentaire</textarea>
        </div>

        <div class="client-form-actions">
            <button type="submit" name="action" value="enregistrer" class="client-btn client-btn-gray">Enregistrer</button>
            <button type="submit" name="action" value="envoyer" class="client-btn client-btn-blue"
                    onclick="return confirm('Une fois envoyé, vous ne pourrez plus modifier. Continuer ?');">
                Envoyer
            </button>
        </div>
    </form>

    <div class="client-card mt-6">
        <h2 class="client-card-title">Conclusion de l'analyste</h2>
        <p class="text-sm text-gray-700">$soumission->conclusion</p>
    </div>

</body>
</html>