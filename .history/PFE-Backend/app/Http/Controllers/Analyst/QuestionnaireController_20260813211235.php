<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Models\UserQuestionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionnaireController extends Controller
{
    //get asswer questionnaire that a client completed and submited so a analyste can revien and add recommendation
    public function index()
    {
         $questionnaires = UserQuestionnaire::where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->get();
            return view('analyst.questionnaire.index', compact('questionnaires'));
    }

    public function show(int $id)
    {
        $soumission = UserQuestionnaire::with('questionnaire.questions.type', 'user')
            ->where('status', 'submitted')
            ->findOrFail($id);

        return view('analyst.questionnaire.show', compact('soumission'));
    }

    public function store(Request $request, int $id)
    {
    $request->validate([
        'conclusion' => 'required|string',
    ]);

    $soumission = UserQuestionnaire::where('status', 'submitted')
        ->findOrFail($id);

    $soumission->update([
    'conclusion' => $request->input('conclusion'),
    'analyst_id'   (int) auth()->id(),
    'status'     => 'under_review',
    ]);
    return redirect()
        ->route('analyst.questionnaire.index')
        ->with('success', 'Recommandation ajoutée avec succès.');
}


}
