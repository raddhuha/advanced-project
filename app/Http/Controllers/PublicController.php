<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterTutorial;

class PublicController extends Controller
{
    public function presentation($url) {
        $tutorial = MasterTutorial::where('url_presentation', $url)->firstOrFail();
        $steps = $tutorial->detailTutorials()->where('status', true)->orderBy('order')->get();
        return view('public.presentation', compact('tutorial', 'steps'));
    }

    public function finished($url) {
        $tutorial = MasterTutorial::where('url_finished', $url)->firstOrFail();
        $steps = $tutorial->detailTutorials()->orderBy('order')->get();
        return view('public.finished', compact('tutorial', 'steps'));
    }

}
