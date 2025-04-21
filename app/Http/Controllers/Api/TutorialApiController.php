<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterTutorial;

class TutorialApiController extends Controller
{
    public function getByKodeMatkul($kode_matkul)
    {
        $tutorials = MasterTutorial::where('course_code', $kode_matkul)->get();

        if ($tutorials->isEmpty()) {
            return response()->json([
                'results' => [],
                'status' => [
                    'code' => 404,
                    'description' => "Not Found data {$kode_matkul}"
                ]
            ], 404);
        }

        // Map tutorial ke bentuk yang diinginkan
        $results = $tutorials->map(function ($tutorial) {
            return [
                'kode_matkul' => $tutorial->course_code,
                'nama_matkul' => $tutorial->course_name,
                'judul' => $tutorial->title,
                'url_presentation' => url("presentation/{$tutorial->url_presentation}"),
                'url_finished' => url("finished/{$tutorial->url_finished}"),
                'creator_email' => $tutorial->creator_email,
                'created_at' => $tutorial->created_at,
                'updated_at' => $tutorial->updated_at,
            ];
        });

        return response()->json([
            'results' => $results,
            'status' => [
                'code' => 200,
                'description' => "OK"
            ]
        ]);
    }
}
