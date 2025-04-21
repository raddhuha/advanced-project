<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterTutorial;

class DashboardController extends Controller
{
    public function index()
    {
        $userEmail = Auth::user()->email;
        $tutorials = MasterTutorial::where('creator_email', $userEmail)
                                  ->latest()
                                  ->paginate(10); // Show 10 tutorials per page

        return view('dashboard', compact('tutorials'));
    }
}
