<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Models\UserQuestionnaire;
use App\Models\UserQuestionnaireAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionnaireController extends Controller
{
    public function index()
    {
        $questionnaires = UserQuestionnaire::with('user', 'questionnaire')
            ->where('analyst_id', Auth::id())
            ->where('status', 'under_review')
            ->get();

        return view('analyst.questionnaire.index', compact('questionnaires'));
    }

    public function show(int $id)
    {
        $soumission = UserQuestionnaire::with('questionnaire.questions.type', 'user')
            ->where('analyst_id', Auth::id())
            ->where('status', 'under_review')
            ->findOrFail($id);

        $reponses = UserQuestionnaireAnswer::where('user_id', $soumission->user_id)
            ->where('questionnaire_id', $soumission->questionnaire_id)
            ->get()
            ->keyBy('question_id');

        return view('analyst.questionnaire.show', compact('soumission', 'reponses'));
    }

    public function store(Request $request, int $id)
    {
        $request->validate([
            'conclusion' => 'required|string',
        ]);

        $soumission = UserQuestionnaire::where('analyst_id', Auth::id())
            ->where('status', 'under_review')
            ->findOrFail($id);

        $soumission->conclusion = $request->input('conclusion');
        $soumission->save();

        return redirect()
            ->route('analyst.questionnaire.index')
            ->with('success', 'Conclusion enregistrée avec succès.');
    }
}
