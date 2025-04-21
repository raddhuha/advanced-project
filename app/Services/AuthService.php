<?php
// File: app/Services/AuthService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthService
{
    protected $baseUrl = 'https://jwt-auth-eight-neon.vercel.app';

    /**
     * Authenticate user with the webservice
     *
     * @param string $email
     * @param string $password
     * @return array|null
     */
    public function login(string $email, string $password): ?array
    {
        try {
            // Make sure we're sending the exact format the API expects
            $payload = [
                'email' => $email,
                'password' => $password,
            ];

            Log::debug('Sending auth request', ['url' => $this->baseUrl . '/login', 'payload' => $payload]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . '/login', $payload);

            Log::debug('Auth response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Check if refreshToken exists in the response
                if (isset($data['refreshToken'])) {
                    // Store the refreshToken in session
                    Session::put('refreshToken', $data['refreshToken']);

                    return $data;
                } else {
                    Log::warning('RefreshToken not found in response', ['response' => $data]);
                    return null;
                }
            } else {
                Log::warning('Authentication failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Authentication exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Get the stored refresh token
     *
     * @return string|null
     */
    public function getRefreshToken(): ?string
    {
        return Session::get('refreshToken');
    }
}
