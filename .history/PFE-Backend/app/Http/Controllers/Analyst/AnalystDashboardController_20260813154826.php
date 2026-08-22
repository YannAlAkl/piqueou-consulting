<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Models\UserQuestionnaire;
use Illuminate\Support\Facades\Auth;

class AnalystDashboardController extends Controller
{
    public function dashboard()
    {
        // Compte les questionnaires réellement assignés à cet analyste
        $total = UserQuestionnaire::where('analyst_id', Auth::id())
            ->whereIn('status', ['submitted', 'under_review'])
            ->count();

        return view('analyst.dashboard', compact('total'));
    }
};
