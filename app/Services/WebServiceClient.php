<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class WebServiceClient
{
    /**
     * Get the list of courses from the webservice.
     *
     * @return array
     */
    public static function getCourses()
    {
        $user = Auth::user();

        if (!$user || !$user->refresh_token) {
            return [];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $user->refresh_token,
            ])->get('https://jwt-auth-eight-neon.vercel.app/getMakul');

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
