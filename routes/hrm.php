<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoreHrmJobPostingController;
use App\Http\Controllers\CoreHrmCandidateController;
use App\Http\Controllers\CoreHrmInterviewController;
use App\Http\Controllers\CoreHrmAssessmentController;

/*
|--------------------------------------------------------------------------
| HRM Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
  // Job Postings
  Route::prefix('job-postings')->group(function () {
    Route::get('/', [CoreHrmJobPostingController::class, 'index'])->name('job-postings.index');
    Route::get('/create', [CoreHrmJobPostingController::class, 'create'])->name('job-postings.create');
    Route::post('/', [CoreHrmJobPostingController::class, 'store'])->name('job-postings.store');
    Route::get('/{jobPosting}', [CoreHrmJobPostingController::class, 'show'])->name('job-postings.show');
    Route::get('/{jobPosting}/edit', [CoreHrmJobPostingController::class, 'edit'])->name('job-postings.edit');
    Route::patch('/{jobPosting}', [CoreHrmJobPostingController::class, 'update'])->name('job-postings.update');
    Route::delete('/{jobPosting}', [CoreHrmJobPostingController::class, 'destroy'])->name('job-postings.destroy');
    Route::get('/dashboard', [CoreHrmJobPostingController::class, 'dashboard'])->name('job-postings.dashboard');
  });

  // Candidates
  Route::prefix('candidates')->group(function () {
    Route::get('/', [CoreHrmCandidateController::class, 'index'])->name('candidates.index');
    Route::get('/create', [CoreHrmCandidateController::class, 'create'])->name('candidates.create');
    Route::post('/', [CoreHrmCandidateController::class, 'store'])->name('candidates.store');
    Route::get('/{candidate}', [CoreHrmCandidateController::class, 'show'])->name('candidates.show');
    Route::get('/{candidate}/edit', [CoreHrmCandidateController::class, 'edit'])->name('candidates.edit');
    Route::patch('/{candidate}', [CoreHrmCandidateController::class, 'update'])->name('candidates.update');
    Route::delete('/{candidate}', [CoreHrmCandidateController::class, 'destroy'])->name('candidates.destroy');
    Route::get('/{candidate}/resume', [CoreHrmCandidateController::class, 'downloadResume'])->name('candidates.download-resume');
    Route::get('/{candidate}/cover-letter', [CoreHrmCandidateController::class, 'downloadCoverLetter'])->name('candidates.download-cover-letter');
  });

  // Interviews
  Route::prefix('interviews')->group(function () {
    Route::get('/', [CoreHrmInterviewController::class, 'index'])->name('interviews.index');
    Route::get('/create', [CoreHrmInterviewController::class, 'create'])->name('interviews.create');
    Route::post('/', [CoreHrmInterviewController::class, 'store'])->name('interviews.store');
    Route::get('/{interview}', [CoreHrmInterviewController::class, 'show'])->name('interviews.show');
    Route::get('/{interview}/edit', [CoreHrmInterviewController::class, 'edit'])->name('interviews.edit');
    Route::patch('/{interview}', [CoreHrmInterviewController::class, 'update'])->name('interviews.update');
    Route::delete('/{interview}', [CoreHrmInterviewController::class, 'destroy'])->name('interviews.destroy');
    Route::get('/calendar', [CoreHrmInterviewController::class, 'calendar'])->name('interviews.calendar');
    Route::patch('/{interview}/complete', [CoreHrmInterviewController::class, 'complete'])->name('interviews.complete');
    Route::patch('/{interview}/cancel', [CoreHrmInterviewController::class, 'cancel'])->name('interviews.cancel');
  });

  // Assessments
  Route::prefix('assessments')->group(function () {
    Route::get('/', [CoreHrmAssessmentController::class, 'index'])->name('assessments.index');
    Route::get('/create', [CoreHrmAssessmentController::class, 'create'])->name('assessments.create');
    Route::post('/', [CoreHrmAssessmentController::class, 'store'])->name('assessments.store');
    Route::get('/{assessment}', [CoreHrmAssessmentController::class, 'show'])->name('assessments.show');
    Route::get('/{assessment}/edit', [CoreHrmAssessmentController::class, 'edit'])->name('assessments.edit');
    Route::patch('/{assessment}', [CoreHrmAssessmentController::class, 'update'])->name('assessments.update');
    Route::delete('/{assessment}', [CoreHrmAssessmentController::class, 'destroy'])->name('assessments.destroy');
    Route::get('/{assessment}/attachment/{index}', [CoreHrmAssessmentController::class, 'downloadAttachment'])->name('assessments.download-attachment');
    Route::patch('/{assessment}/start', [CoreHrmAssessmentController::class, 'start'])->name('assessments.start');
    Route::patch('/{assessment}/complete', [CoreHrmAssessmentController::class, 'complete'])->name('assessments.complete');
  });

  // API Routes for AJAX Requests
  Route::prefix('api')->group(function () {
    Route::get('/job-postings/{jobPosting}/candidates', [CoreHrmJobPostingController::class, 'getCandidates']);
  });
});
