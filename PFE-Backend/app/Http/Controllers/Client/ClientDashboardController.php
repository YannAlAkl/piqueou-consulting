<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientDashboardController extends Controller
{

public function index()
{
    $questionnaires = DB::table('questionnaires')
        ->select(
            'id',
            'name',
            'description',
            'created_at',
            'updated_at'
        )
        ->get();

    return view(
        'client.dashboard',
        compact('questionnaires')
    );
}


/**
 * Afficher les questions d'un questionnaire.
 */
public function show(int $id)
{
    $questions = DB::table('questions')
        ->select(
            'id',
            'questionnaire_id',
            'question_type_id',
            'question',
            'description',
            'options',
            'position',
            'required',
            'created_at',
            'updated_at'
        )
        ->where('questionnaire_id', $id)
        ->orderBy('position')
        ->get();

    if ($questions->isEmpty()) {
        abort(404);
    }

    $questionnaire = DB::table('questionnaires')
        ->select(
            'id',
            'name',
            'description'
        )
        ->where('id', $id)
        ->first();

    if (!$questionnaire) {
        abort(404);
    }

    return view(
        'client.dashboard',
        compact('questionnaire', 'questions')
    );
}


/**
 * Enregistrer les réponses.
 */
public function submit(Request $request, int $id)
{
    $validated = $request->validate([
        'answers' => 'required|array',
        'answers.*' => 'nullable|string',
    ]);

    foreach ($validated['answers'] as $questionId => $answer) {

        DB::table('answers')->insert([
            'question_id' => $questionId,
            'user_id' => auth()->id(),
            'answer' => $answer,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return redirect()
        ->route('client.questionnaire.show', $id)
        ->with(
            'success',
            'Les réponses ont été enregistrées avec succès.'
        );
}
        }

