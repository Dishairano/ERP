<?php

namespace App\Http\Controllers;

use App\Models\CoreHrmCandidateModal;
use App\Models\CoreHrmJobPostingModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CoreHrmCandidateController extends Controller
{
  /**
   * Display a listing of candidates.
   */
  public function index(Request $request)
  {
    $query = CoreHrmCandidateModal::with(['jobPosting', 'interviews', 'assessments']);

    // Apply filters
    if ($request->has('job_posting_id')) {
      $query->where('job_posting_id', $request->job_posting_id);
    }

    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    if ($request->has('experience_years')) {
      $query->where('experience_years', '>=', $request->experience_years);
    }

    if ($request->has('education_level')) {
      $query->where('education_level', $request->education_level);
    }

    // Search
    if ($request->has('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('first_name', 'like', "%{$search}%")
          ->orWhere('last_name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%")
          ->orWhere('current_company', 'like', "%{$search}%")
          ->orWhere('current_position', 'like', "%{$search}%");
      });
    }

    // Sort
    $sortField = $request->input('sort_field', 'created_at');
    $sortDirection = $request->input('sort_direction', 'desc');
    $query->orderBy($sortField, $sortDirection);

    $candidates = $query->paginate(10);

    return view('core.hrm.candidates.index', [
      'candidates' => $candidates,
      'jobPostings' => CoreHrmJobPostingModal::all(),
      'statuses' => CoreHrmCandidateModal::getStatuses(),
      'educationLevels' => CoreHrmCandidateModal::getEducationLevels()
    ]);
  }

  /**
   * Show the form for creating a new candidate.
   */
  public function create(Request $request)
  {
    $jobPosting = null;
    if ($request->has('job_posting_id')) {
      $jobPosting = CoreHrmJobPostingModal::findOrFail($request->job_posting_id);
    }

    return view('core.hrm.candidates.create', [
      'jobPosting' => $jobPosting,
      'jobPostings' => CoreHrmJobPostingModal::active()->get(),
      'educationLevels' => CoreHrmCandidateModal::getEducationLevels(),
      'salaryPeriods' => CoreHrmCandidateModal::getSalaryPeriods()
    ]);
  }

  /**
   * Store a newly created candidate.
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'job_posting_id' => 'required|exists:hrm_job_postings,id',
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'email' => 'required|email|unique:hrm_candidates,email',
      'phone' => 'nullable|string|max:255',
      'address' => 'nullable|string',
      'city' => 'nullable|string|max:255',
      'state' => 'nullable|string|max:255',
      'country' => 'nullable|string|max:255',
      'postal_code' => 'nullable|string|max:255',
      'current_company' => 'nullable|string|max:255',
      'current_position' => 'nullable|string|max:255',
      'experience_years' => 'nullable|numeric|min:0',
      'education_level' => 'nullable|string',
      'field_of_study' => 'nullable|string|max:255',
      'skills' => 'nullable|array',
      'certifications' => 'nullable|array',
      'languages' => 'nullable|array',
      'resume' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
      'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
      'portfolio_url' => 'nullable|url',
      'linkedin_url' => 'nullable|url',
      'github_url' => 'nullable|url',
      'other_urls' => 'nullable|array',
      'source' => 'nullable|string|max:255',
      'referral_source' => 'nullable|string|max:255',
      'expected_salary' => 'nullable|numeric|min:0',
      'salary_currency' => 'required|string|size:3',
      'salary_period' => 'nullable|string',
      'available_from' => 'nullable|date',
      'notice_period' => 'nullable|string|max:255',
      'willing_to_relocate' => 'boolean',
      'visa_status' => 'nullable|string|max:255'
    ]);

    if ($validator->fails()) {
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = $request->all();

    // Handle file uploads
    if ($request->hasFile('resume')) {
      $data['resume_path'] = $request->file('resume')->store('candidates/resumes');
    }

    if ($request->hasFile('cover_letter')) {
      $data['cover_letter_path'] = $request->file('cover_letter')->store('candidates/cover_letters');
    }

    $candidate = new CoreHrmCandidateModal($data);
    $candidate->created_by = Auth::id();
    $candidate->save();

    return redirect()
      ->route('candidates.show', $candidate)
      ->with('success', 'Candidate created successfully.');
  }

  /**
   * Display the specified candidate.
   */
  public function show(CoreHrmCandidateModal $candidate)
  {
    $candidate->load([
      'jobPosting',
      'interviews' => function ($query) {
        $query->orderBy('scheduled_date', 'desc');
      },
      'assessments' => function ($query) {
        $query->orderBy('scheduled_date', 'desc');
      },
      'creator'
    ]);

    return view('core.hrm.candidates.show', [
      'candidate' => $candidate
    ]);
  }

  /**
   * Show the form for editing the specified candidate.
   */
  public function edit(CoreHrmCandidateModal $candidate)
  {
    return view('core.hrm.candidates.edit', [
      'candidate' => $candidate,
      'jobPostings' => CoreHrmJobPostingModal::all(),
      'educationLevels' => CoreHrmCandidateModal::getEducationLevels(),
      'salaryPeriods' => CoreHrmCandidateModal::getSalaryPeriods(),
      'statuses' => CoreHrmCandidateModal::getStatuses()
    ]);
  }

  /**
   * Update the specified candidate.
   */
  public function update(Request $request, CoreHrmCandidateModal $candidate)
  {
    $validator = Validator::make($request->all(), [
      'job_posting_id' => 'required|exists:hrm_job_postings,id',
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'email' => 'required|email|unique:hrm_candidates,email,' . $candidate->id,
      'phone' => 'nullable|string|max:255',
      'address' => 'nullable|string',
      'city' => 'nullable|string|max:255',
      'state' => 'nullable|string|max:255',
      'country' => 'nullable|string|max:255',
      'postal_code' => 'nullable|string|max:255',
      'current_company' => 'nullable|string|max:255',
      'current_position' => 'nullable|string|max:255',
      'experience_years' => 'nullable|numeric|min:0',
      'education_level' => 'nullable|string',
      'field_of_study' => 'nullable|string|max:255',
      'skills' => 'nullable|array',
      'certifications' => 'nullable|array',
      'languages' => 'nullable|array',
      'resume' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
      'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
      'portfolio_url' => 'nullable|url',
      'linkedin_url' => 'nullable|url',
      'github_url' => 'nullable|url',
      'other_urls' => 'nullable|array',
      'source' => 'nullable|string|max:255',
      'referral_source' => 'nullable|string|max:255',
      'expected_salary' => 'nullable|numeric|min:0',
      'salary_currency' => 'required|string|size:3',
      'salary_period' => 'nullable|string',
      'available_from' => 'nullable|date',
      'notice_period' => 'nullable|string|max:255',
      'willing_to_relocate' => 'boolean',
      'visa_status' => 'nullable|string|max:255',
      'status' => 'required|string',
      'rejection_reason' => 'nullable|string|required_if:status,rejected'
    ]);

    if ($validator->fails()) {
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = $request->all();

    // Handle file uploads
    if ($request->hasFile('resume')) {
      if ($candidate->resume_path) {
        Storage::delete($candidate->resume_path);
      }
      $data['resume_path'] = $request->file('resume')->store('candidates/resumes');
    }

    if ($request->hasFile('cover_letter')) {
      if ($candidate->cover_letter_path) {
        Storage::delete($candidate->cover_letter_path);
      }
      $data['cover_letter_path'] = $request->file('cover_letter')->store('candidates/cover_letters');
    }

    $candidate->update($data);

    return redirect()
      ->route('candidates.show', $candidate)
      ->with('success', 'Candidate updated successfully.');
  }

  /**
   * Remove the specified candidate.
   */
  public function destroy(CoreHrmCandidateModal $candidate)
  {
    // Delete associated files
    if ($candidate->resume_path) {
      Storage::delete($candidate->resume_path);
    }
    if ($candidate->cover_letter_path) {
      Storage::delete($candidate->cover_letter_path);
    }

    $candidate->delete();

    return redirect()
      ->route('candidates.index')
      ->with('success', 'Candidate deleted successfully.');
  }

  /**
   * Download candidate's resume.
   */
  public function downloadResume(CoreHrmCandidateModal $candidate)
  {
    if (!$candidate->resume_path || !Storage::exists($candidate->resume_path)) {
      abort(404);
    }

    return Storage::download(
      $candidate->resume_path,
      $candidate->first_name . '_' . $candidate->last_name . '_resume.' .
        pathinfo($candidate->resume_path, PATHINFO_EXTENSION)
    );
  }

  /**
   * Download candidate's cover letter.
   */
  public function downloadCoverLetter(CoreHrmCandidateModal $candidate)
  {
    if (!$candidate->cover_letter_path || !Storage::exists($candidate->cover_letter_path)) {
      abort(404);
    }

    return Storage::download(
      $candidate->cover_letter_path,
      $candidate->first_name . '_' . $candidate->last_name . '_cover_letter.' .
        pathinfo($candidate->cover_letter_path, PATHINFO_EXTENSION)
    );
  }
}
