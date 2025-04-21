<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.custom-login');
    }

    public function login(Request $request)
    {
        $response = Http::post('https://jwt-auth-eight-neon.vercel.app/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $tokenData = $response->json();
            session()->put('refreshToken', $tokenData['refreshToken'] ?? null);
            session()->put('userEmail', $request->email);

            return redirect('/dashboard')->with('success', 'Berhasil login!');
        }

        return back()->with('error', 'Login gagal. Periksa kembali email & password.');
    }

}
