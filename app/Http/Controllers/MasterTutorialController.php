<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterTutorial;
use App\Services\WebServiceClient;
use PDF;

class MasterTutorialController extends Controller
{
    public function index()
    {
        $tutorials = MasterTutorial::all();
        return view('tutorials.index', compact('tutorials'));
    }

    public function create()
    {
        // Fetch courses from webservice
        $response = WebServiceClient::getCourses();
        $courses = $response['data'] ?? [];
        return view('tutorials.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'course_code' => 'required|string|max:50',
        ]);

        $userEmail = Auth::user()->email;

        // Generate unique URLs
        $urlPresentation = $this->generateUniqueUrl('url_presentation');
        $urlFinished = $this->generateUniqueUrl('url_finished');

        MasterTutorial::create([
            'title' => $request->title,
            'course_code' => $request->course_code,
            'url_presentation' => $urlPresentation,
            'url_finished' => $urlFinished,
            'creator_email' => $userEmail,
        ]);

        return redirect()->route('dashboard')->with('success', 'Tutorial berhasil dibuat!');
    }

    public function show($id)
    {
        $tutorial = MasterTutorial::with('detailTutorials')->findOrFail($id);
        return view('tutorials.show', compact('tutorial'));
    }

    public function edit($id)
    {
        $tutorial = MasterTutorial::findOrFail($id);

        // Fetch courses from webservice
        $response = WebServiceClient::getCourses();
        $courses = $response['data'] ?? [];
        return view('tutorials.edit', compact('tutorial', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'course_code' => 'required|string|max:50',
        ]);

        $tutorial = MasterTutorial::findOrFail($id);
        $tutorial->update($request->only('title', 'course_code'));

        return redirect()->route('dashboard')->with('success', 'Tutorial berhasil diperbarui');
    }

    public function destroy($id)
    {
        $tutorial = MasterTutorial::findOrFail($id);
        $tutorial->delete();

        return redirect()->route('dashboard')->with('success', 'Tutorial berhasil dihapus!');
    }

    public function presentation($url)
    {
        $master = MasterTutorial::where('url_presentation', $url)->firstOrFail();
        $steps = $master->detailTutorials()->where('status', true)->orderBy('order')->get();
        return view('presentation', compact('master', 'steps'));
    }

    public function finished($url)
    {
        $master = MasterTutorial::where('url_finished', $url)->firstOrFail();
        $steps = $master->detailTutorials()->orderBy('order')->get();
        return view('finished', compact('master', 'steps'));
    }

    public function exportPdf($url_finished)
    {
        $tutorial = MasterTutorial::with(['detailTutorials' => function ($query) {
            $query->orderBy('order');
        }])->where('url_finished', $url_finished)->firstOrFail();

        $pdf = PDF::loadView('tutorials.export', compact('tutorial'));
        return $pdf->download('tutorial-'.$tutorial->title.'.pdf');
    }

    /**
     * Generate unique URL for tutorial
     *
     * @param string $field Field name to check uniqueness
     * @return string
     */
    private function generateUniqueUrl($field)
    {
        $url = Str::random(10);

        // Check if URL already exists
        while (MasterTutorial::where($field, $url)->exists()) {
            $url = Str::random(10);
        }

        return $url;
    }
}
