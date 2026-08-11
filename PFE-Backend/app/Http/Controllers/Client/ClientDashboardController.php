<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\UserQuestionnaireAnswer;
use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $questionnaires = Questionnaire::select(
            'id',
            'title',
            'description',
            'status'
        )
        ->orderBy('title')
        ->get();

        return view('client.dashboard', compact('questionnaires'));
    }

    /**
     * Afficher les questions d'un questionnaire.
     */
    public function show(int $id)
    {
        $questionnaire = Questionnaire::with(['questions' => function ($query) {
            $query->orderBy('position');
        }])->findOrFail($id);

        return view('client.dashboard', compact('questionnaire'));
    }

    /**
     * Enregistrer les réponses.
     */
    public function submit(Request $request, int $id)
    {
        $questionnaire = Questionnaire::findOrFail($id);

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|string',
        ]);

        foreach ($validated['answers'] as $questionId => $answer) {
            UserQuestionnaireAnswer::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'questionnaire_id' => $questionnaire->id,
                    'question_id' => $questionId,
                ],
                [
                    'answer' => $answer,
                    'answered_at' => now(),
                ]
            );
        }

        return redirect()
            ->route('client.questionnaire.show', $questionnaire->id)
            ->with('success', 'Les réponses ont été enregistrées avec succès.');
    }
}

