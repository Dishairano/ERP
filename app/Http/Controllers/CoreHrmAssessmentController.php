<?php

namespace App\Http\Controllers;

use App\Models\CoreHrmAssessmentModal;
use App\Models\CoreHrmCandidateModal;
use App\Models\CoreHrmJobPostingModal;
use App\Models\CoreHrmEmployeeModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CoreHrmAssessmentController extends Controller
{
  /**
   * Display a listing of assessments.
   */
  public function index(Request $request)
  {
    $query = CoreHrmAssessmentModal::with(['jobPosting', 'candidate', 'assessor']);

    // Apply filters
    if ($request->has('job_posting_id')) {
      $query->where('job_posting_id', $request->job_posting_id);
    }

    if ($request->has('candidate_id')) {
      $query->where('candidate_id', $request->candidate_id);
    }

    if ($request->has('assessor_id')) {
      $query->where('assessor_id', $request->assessor_id);
    }

    if ($request->has('assessment_type')) {
      $query->where('assessment_type', $request->assessment_type);
    }

    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    if ($request->has('date_from')) {
      $query->where('scheduled_date', '>=', $request->date_from);
    }

    if ($request->has('date_to')) {
      $query->where('scheduled_date', '<=', $request->date_to);
    }

    // Search
    if ($request->has('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('title', 'like', "%{$search}%")
          ->orWhere('description', 'like', "%{$search}%")
          ->orWhereHas('candidate', function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%");
          });
      });
    }

    // Sort
    $sortField = $request->input('sort_field', 'scheduled_date');
    $sortDirection = $request->input('sort_direction', 'desc');
    $query->orderBy($sortField, $sortDirection);

    $assessments = $query->paginate(10);

    return view('core.hrm.assessments.index', [
      'assessments' => $assessments,
      'jobPostings' => CoreHrmJobPostingModal::all(),
      'assessmentTypes' => CoreHrmAssessmentModal::getAssessmentTypes(),
      'statuses' => CoreHrmAssessmentModal::getStatuses()
    ]);
  }

  /**
   * Show the form for creating a new assessment.
   */
  public function create(Request $request)
  {
    $candidate = null;
    $jobPosting = null;

    if ($request->has('candidate_id')) {
      $candidate = CoreHrmCandidateModal::findOrFail($request->candidate_id);
      $jobPosting = $candidate->jobPosting;
    }

    return view('core.hrm.assessments.create', [
      'candidate' => $candidate,
      'jobPosting' => $jobPosting,
      'candidates' => CoreHrmCandidateModal::whereIn('status', ['applied', 'screening', 'interviewing'])->get(),
      'assessors' => CoreHrmEmployeeModal::active()->get(),
      'assessmentTypes' => CoreHrmAssessmentModal::getAssessmentTypes()
    ]);
  }

  /**
   * Store a newly created assessment.
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'job_posting_id' => 'required|exists:hrm_job_postings,id',
      'candidate_id' => 'required|exists:hrm_candidates,id',
      'assessor_id' => 'required|exists:hrm_employees,id',
      'assessment_type' => 'required|string',
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'scheduled_date' => 'required|date',
      'scheduled_time' => 'required|date_format:Y-m-d H:i:s',
      'duration_minutes' => 'required|integer|min:1',
      'platform' => 'nullable|string|max:255',
      'access_link' => 'nullable|url',
      'access_code' => 'nullable|string|max:255',
      'instructions' => 'nullable|string',
      'questions' => 'nullable|array',
      'max_score' => 'required|numeric|min:0',
      'passing_score' => 'required|numeric|min:0|lte:max_score',
      'skills_evaluated' => 'nullable|array',
      'attachments.*' => 'nullable|file|max:10240'
    ]);

    if ($validator->fails()) {
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = $request->all();

    // Handle attachments
    if ($request->hasFile('attachments')) {
      $attachments = [];
      foreach ($request->file('attachments') as $file) {
        $attachments[] = $file->store('assessments/attachments');
      }
      $data['attachments'] = $attachments;
    }

    $assessment = new CoreHrmAssessmentModal($data);
    $assessment->created_by = Auth::id();
    $assessment->save();

    return redirect()
      ->route('assessments.show', $assessment)
      ->with('success', 'Assessment created successfully.');
  }

  /**
   * Display the specified assessment.
   */
  public function show(CoreHrmAssessmentModal $assessment)
  {
    $assessment->load(['jobPosting', 'candidate', 'assessor', 'creator']);

    return view('core.hrm.assessments.show', [
      'assessment' => $assessment
    ]);
  }

  /**
   * Show the form for editing the specified assessment.
   */
  public function edit(CoreHrmAssessmentModal $assessment)
  {
    return view('core.hrm.assessments.edit', [
      'assessment' => $assessment,
      'candidates' => CoreHrmCandidateModal::whereIn('status', ['applied', 'screening', 'interviewing'])->get(),
      'assessors' => CoreHrmEmployeeModal::active()->get(),
      'assessmentTypes' => CoreHrmAssessmentModal::getAssessmentTypes(),
      'statuses' => CoreHrmAssessmentModal::getStatuses()
    ]);
  }

  /**
   * Update the specified assessment.
   */
  public function update(Request $request, CoreHrmAssessmentModal $assessment)
  {
    $validator = Validator::make($request->all(), [
      'job_posting_id' => 'required|exists:hrm_job_postings,id',
      'candidate_id' => 'required|exists:hrm_candidates,id',
      'assessor_id' => 'required|exists:hrm_employees,id',
      'assessment_type' => 'required|string',
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'scheduled_date' => 'required|date',
      'scheduled_time' => 'required|date_format:Y-m-d H:i:s',
      'duration_minutes' => 'required|integer|min:1',
      'platform' => 'nullable|string|max:255',
      'access_link' => 'nullable|url',
      'access_code' => 'nullable|string|max:255',
      'instructions' => 'nullable|string',
      'questions' => 'nullable|array',
      'answers' => 'nullable|array',
      'score' => 'nullable|numeric|min:0|lte:max_score',
      'max_score' => 'required|numeric|min:0',
      'passing_score' => 'required|numeric|min:0|lte:max_score',
      'skills_evaluated' => 'nullable|array',
      'skill_scores' => 'nullable|array',
      'feedback' => 'nullable|string',
      'recommendations' => 'nullable|string',
      'attachments.*' => 'nullable|file|max:10240',
      'status' => 'required|string',
      'completion_date' => 'nullable|date|required_if:status,completed',
      'expiry_date' => 'nullable|date|after:today',
      'notes' => 'nullable|string'
    ]);

    if ($validator->fails()) {
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = $request->all();

    // Handle attachments
    if ($request->hasFile('attachments')) {
      $existingAttachments = $assessment->attachments ?? [];
      $newAttachments = [];
      foreach ($request->file('attachments') as $file) {
        $newAttachments[] = $file->store('assessments/attachments');
      }
      $data['attachments'] = array_merge($existingAttachments, $newAttachments);
    }

    $assessment->update($data);

    return redirect()
      ->route('assessments.show', $assessment)
      ->with('success', 'Assessment updated successfully.');
  }

  /**
   * Remove the specified assessment.
   */
  public function destroy(CoreHrmAssessmentModal $assessment)
  {
    // Delete associated files
    if (!empty($assessment->attachments)) {
      foreach ($assessment->attachments as $attachment) {
        Storage::delete($attachment);
      }
    }

    $assessment->delete();

    return redirect()
      ->route('assessments.index')
      ->with('success', 'Assessment deleted successfully.');
  }

  /**
   * Download assessment attachment.
   */
  public function downloadAttachment(CoreHrmAssessmentModal $assessment, $index)
  {
    if (
      !isset($assessment->attachments[$index]) ||
      !Storage::exists($assessment->attachments[$index])
    ) {
      abort(404);
    }

    return Storage::download($assessment->attachments[$index]);
  }
}
