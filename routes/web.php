<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamAttemptController;
use App\Http\Controllers\QuestionController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:lecturer'])->group(function () {
    Route::resource('exams', ExamController::class);
    Route::post('exams/{exam}/questions', [QuestionController::class, 'store']);
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('exams/{exam}/start', [ExamAttemptController::class, 'start']);
    Route::get('exams/{exam}/take', [ExamAttemptController::class, 'take'])->name('exams.take');
    Route::post('exams/{exam}/submit', [ExamAttemptController::class, 'submit']);
});


require __DIR__ . '/settings.php';
