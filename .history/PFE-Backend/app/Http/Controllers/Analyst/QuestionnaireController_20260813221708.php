<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Models\UserQuestionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionnaireController extends Controller
{
    // Liste des questionnaires soumis ET assignés à l'analyste connecté
    public function index()
    {
        $questionnaires = UserQuestionnaire::with('user', 'questionnaire')
            ->where('analyst_id', Auth::id())
            ->whereIn('status', ['submitted', 'under_review'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('analyst.questionnaire.index', compact('questionnaires'));
    }

    public function show(int $id)
    {
        $soumission = UserQuestionnaire::with('questionnaire.questions.type', 'user')
            ->where('analyst_id', Auth::id())
            ->whereIn('status', ['submitted', 'under_review'])
            ->findOrFail($id);

        return view('analyst.questionnaire.show', compact('soumission'));
    }

    public function store(Request $request, int $id)
    {
        $request->validate([
            'conclusion' => 'required|string',
        ]);

        $soumission = UserQuestionnaire::where('analyst_id', Auth::id())
            ->whereIn('status', ['submitted', 'under_review'])
            ->findOrFail($id);

        $soumission->update([
            'conclusion' => $request->input('conclusion'),
            'status'     => 'under_review',
        ]);

        return redirect()
            ->route('analyst.questionnaire.index')
            ->with('success', 'Recommandation ajoutée avec succès.');
    }
}