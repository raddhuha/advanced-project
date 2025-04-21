<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
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
            $payload = [
                'email' => $email,
                'password' => $password,
            ];

            Log::debug('Sending auth request', ['url' => $this->baseUrl . '/login']);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . '/login', $payload);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['refreshToken'])) {
                    return $data;
                }

                Log::warning('RefreshToken not found in response');
                return null;
            }

            Log::warning('Authentication failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('Authentication exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Make authenticated API requests
     *
     * @param string $endpoint
     * @param string $refreshToken
     * @return array|null
     */
    public function makeAuthenticatedRequest(string $endpoint, string $refreshToken): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $refreshToken,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . $endpoint);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('API request failed', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('API request exception', [
                'message' => $e->getMessage(),
                'endpoint' => $endpoint,
            ]);
            return null;
        }
    }
}
