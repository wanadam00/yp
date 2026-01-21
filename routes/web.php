<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
// use App\Http\Controllers\ExamController;
// use App\Http\Controllers\QuestionController;
use App\Http\Controllers\Lecturer\ExamController;
use App\Http\Controllers\Lecturer\QuestionController;
use App\Http\Controllers\ExamAttemptController;
use App\Http\Controllers\Lecturer\ClassStudentController;
use App\Http\Controllers\Lecturer\ExamPreviewController;
use App\Http\Controllers\Lecturer\GradingController;
use App\Http\Controllers\Lecturer\QuestionOptionController;
use App\Http\Controllers\Student\StudentExamController;
use App\Http\Controllers\Student\StudentAnswerController;
use App\Http\Controllers\Student\StudentExamResultController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:lecturer'])->group(function () {
    Route::resource('/exams', ExamController::class);
    Route::resource('/questions', QuestionController::class);
    Route::resource('/question-options', QuestionOptionController::class);
    Route::get('/exams/{exam}/preview', [ExamPreviewController::class, 'show'])->name('exams.preview');
    Route::get('/exams/{exam}/assign', [ExamController::class, 'assign'])->name('exams.assign');
    Route::post('/exams/{exam}/assign', [ExamController::class, 'storeAssignment']);
    Route::resource('class-student', ClassStudentController::class);
    // Route::resource('grading', GradingController::class);
    Route::get('/grading', [GradingController::class, 'index'])->name('grading.index');
    Route::get('/grading/{attempt}', [GradingController::class, 'show'])->name('grading.show');
    Route::patch('/grading/answers/{answer}', [GradingController::class, 'update'])->name('grading.update');
    Route::patch('/grading/attempts/{attempt}/bulk-grade', [GradingController::class, 'bulkGrade'])
        ->name('grading.bulk-grade');
});

Route::middleware(['auth', 'role:student'])->group(function () {

    Route::get(
        'student/exams',
        [StudentExamController::class, 'index']
    )->name('student.exams.index');

    Route::post(
        'student/exams/{exam}/start',
        [StudentExamController::class, 'start']
    )->name('student.exams.start');

    Route::get(
        'student/exams/{exam}',
        [StudentExamController::class, 'show']
    )->name('student.exams.show');

    Route::post(
        'student/answers',
        [StudentAnswerController::class, 'store']
    )->name('student.answers.store');

    Route::post(
        'student/exam-attempts/{attempt}/submit',
        [StudentExamResultController::class, 'submit']
    )->name('student.exams.submit');

    Route::get(
        'student/results/{attempt}',
        [StudentExamResultController::class, 'show']
    )->name('student.results.show');
});


require __DIR__ . '/settings.php';
