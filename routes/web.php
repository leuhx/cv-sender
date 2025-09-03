<?php

use App\Http\Controllers\Admin\AdminFormController;
use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    $user = Auth::user();

    if ($user->role->value === 'admin') {
        // Dashboard para admin - redireciona para a lista de formulários
        $forms = \App\Models\Form::with('user')->latest()->paginate(10);
        $totalForms = \App\Models\Form::count();
        $totalUsers = \App\Models\User::applicants()->count();
        $recentForms = \App\Models\Form::where('created_at', '>=', now()->subWeek())->count();

        return Inertia::render('Dashboard', [
            'forms' => $forms,
            'totalForms' => $totalForms,
            'totalUsers' => $totalUsers,
            'recentForms' => $recentForms,
        ]);
    } else {
        // Dashboard para applicant - redireciona para seus formulários
        return redirect()->route('forms.index');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Rotas para aplicantes (candidatos)
Route::middleware(['auth', 'verified', 'applicant'])->group(function () {
    Route::resource('forms', FormController::class);
});

// Rotas para administradores
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/forms', [AdminFormController::class, 'index'])->name('forms.index');
    Route::get('/forms/export', [AdminFormController::class, 'export'])->name('forms.export');
    Route::get('/forms/{form}', [AdminFormController::class, 'show'])->name('forms.show');
    Route::delete('/forms/{form}', [AdminFormController::class, 'destroy'])->name('forms.destroy');
    Route::get('/forms/{form}/download-cv', [AdminFormController::class, 'downloadCv'])->name('forms.download-cv');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
