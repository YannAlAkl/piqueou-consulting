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

    public 


}
