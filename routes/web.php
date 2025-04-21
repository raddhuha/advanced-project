<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailTutorialController;
use App\Http\Controllers\MasterTutorialController;
use App\Http\Controllers\Auth\JwtAuthController;

Route::get('/', function () {
    return view('auth.login');
});

// Custom JWT authentication routes
Route::post('/jwt-login', [JwtAuthController::class, 'login'])->name('jwt.login');
Route::post('/jwt-logout', [JwtAuthController::class, 'logout'])->name('jwt.logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tutorial routes
    Route::resource('tutorials', MasterTutorialController::class);

    // Tutorial steps routes
    Route::post('/tutorials/{tutorial}/steps', [DetailTutorialController::class, 'store'])->name('tutorial.steps.store');
    Route::post('/tutorials/steps/{step}/toggle-visibility', [DetailTutorialController::class, 'toggleVisibility'])->name('tutorial.steps.toggle');
    Route::put('/tutorials/steps/{step}', [DetailTutorialController::class, 'update'])->name('tutorial.steps.update');
    Route::get('/tutorials/steps/{step}/edit', [DetailTutorialController::class, 'edit'])->name('tutorial.steps.edit');
    Route::delete('/tutorials/steps/{step}', [DetailTutorialController::class, 'destroy'])->name('tutorial.steps.destroy');
});

//Public routes
Route::get('/presentation/{url}', [PublicController::class, 'presentation'])->name('public.presentation');
Route::get('/finished/{url}', [PublicController::class, 'finished'])->name('public.finished');
Route::get('/presentation/pdf/{url_finished}', [MasterTutorialController::class, 'exportPdf'])->name('public.finished.pdf');

require __DIR__.'/auth.php';
