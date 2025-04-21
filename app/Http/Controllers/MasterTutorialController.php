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
            'course_name' => 'required|string|max:255',
        ]);

        $userEmail = Auth::user()->email;

        // Generate unique URLs
        $urlPresentation = $this->generateUniqueUrl('url_presentation', $request->course_code, $request->title);
        $urlFinished = $this->generateUniqueUrl('url_finished', $request->course_code, $request->title);


        MasterTutorial::create([
            'title' => $request->title,
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
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
            'course_name' => 'required|string|max:255',
        ]);

        $tutorial = MasterTutorial::findOrFail($id);
        $tutorial->update($request->only('title', 'course_code', 'course_name'));

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

        // Process image paths for PDF
        foreach ($tutorial->detailTutorials as $step) {
            if ($step->type === 'image') {
                // Convert storage paths to absolute file system paths
                $step->absolute_image_path = storage_path('app/public/' . $step->content);

                // For debugging
                \Log::info('Image path for PDF: ' . $step->absolute_image_path);
                \Log::info('Image exists: ' . (file_exists($step->absolute_image_path) ? 'Yes' : 'No'));
            }
        }

        $pdf = \PDF::loadView('tutorials.export', compact('tutorial'));

        // Configure PDF options for better image handling
        $pdf->setOption('enable-local-file-access', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isHtml5ParserEnabled', true);
        return $pdf->download('tutorial-'.$tutorial->title.'.pdf');
    }

    private function generateUniqueUrl($field, $courseCode, $title)
    {
        $slugTitle = Str::slug($title); // Ubah jadi lowercase dan strip
        do {
            $url = "{$courseCode}-{$slugTitle}-" . Str::random(10);
        } while (MasterTutorial::where($field, $url)->exists());

        return $url;
    }
}
