<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;

class AnalystDashboardController extends Controller
{
    public function dashboard()
    {
        $total = 0;

        return view('analyst.dashboard', compact('total'));
    }
}
