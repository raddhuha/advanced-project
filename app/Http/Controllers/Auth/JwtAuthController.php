<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class JwtAuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            // Make request to JWT authentication service
            $response = Http::post('https://jwt-auth-eight-neon.vercel.app/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            // Check if the request was successful
            if ($response->successful()) {
                $responseData = $response->json();

                // Check if we have the refreshToken in the response
                if (isset($responseData['refreshToken'])) {
                    $refreshToken = $responseData['refreshToken'];

                    // Get or create user in our local database
                    $user = User::firstOrCreate(
                        ['email' => $request->email],
                        [
                            'name' => $responseData['user']['name'] ?? 'User',
                            'password' => bcrypt($request->password), // Store hashed password
                        ]
                    );

                    // Store the refresh token in the database
                    $user->refresh_token = $refreshToken;
                    $user->save();

                    // Manually log in the user
                    Auth::login($user);

                    $request->session()->regenerate();

                    return redirect()->intended('dashboard');
                }
            }

            // If the response was not successful or didn't contain a refreshToken
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);

        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'email' => ['Authentication failed. Please try again.'],
            ]);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
