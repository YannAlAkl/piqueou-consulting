<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Models\UserQuestionnaire;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    //get asswer questionnaire that a client completed and submited so a analyste can revien and add recommendation
    public function index()
    {
         $questionnaires = UserQuestionnaire::where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function show(int $id)
    {
        $soumission = UserQuestionnaire::with('questionnaire.questions.type', 'user')
            ->where('status', 'submitted')
            ->findOrFail($id);

        return view('analyst.questionnaire.show', compact('soumission'));
    }

    public function store (Request $request, int $id)
    {
        $soumission = UserQuestionnaire::where('status', 'submitted')
            ->findOrFail($id);

        $soumission->update([
            'recommendation' => $request->input('recommendation'),
            'status' => 'under',
        ]);

        return redirect()->route('analyst.questionnaire.index')->with('success', 'Recommandation ajoutée avec succès.');
    }


}
