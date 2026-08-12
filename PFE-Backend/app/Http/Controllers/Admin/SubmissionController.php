<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AnalystAssignedMail;
use App\Models\User;
use App\Models\UserQuestionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubmissionController extends Controller
{
    public function index()
    {
        $soumissions = UserQuestionnaire::with('user', 'questionnaire', 'analyst')
            ->whereIn('status', ['submitted', 'under_review', 'completed'])
            ->orderByDesc('submitted_at')
            ->paginate(15);

        $analystes = User::whereHas('roles', fn($q) => $q->where('name', 'analyst'))
            ->where('account_status', 'active')
            ->orderBy('first_name')
            ->get();

        return view('admin.submission.index', compact('soumissions', 'analystes'));
    }

    public function assign(Request $request, $id)
    {
        $validated = $request->validate([
            'analyst_id' => 'required|exists:users,id',
        ]);

        $soumission = UserQuestionnaire::with('user', 'questionnaire')->findOrFail($id);

        if ($soumission->status === 'completed') {
            return back()->with('error', 'Ce dossier est déjà terminé.');
        }

        $analyste = User::findOrFail($validated['analyst_id']);

        if (! $analyste->hasRole('analyst')) {
            return back()->with('error', 'Cet utilisateur n\'est pas un analyste.');
        }

        $soumission->analyst_id = $analyste->id;
        $soumission->status = 'under_review';
        $soumission->save();

        $message = 'Dossier assigné à ' . $analyste->name . '. Un email lui a été envoyé.';

        try {
            Mail::to($analyste->email)->send(new AnalystAssignedMail($soumission, $analyste));
        } catch (\Exception $e) {
            $message = 'Dossier assigné à ' . $analyste->name . ' mais l\'email n\'a pas pu être envoyé.';
        }

        return back()->with('success', $message);
    }
}
