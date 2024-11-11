<?php

namespace App\Http\Controllers;

use App\Models\CoreHrmInterviewModal;
use App\Models\CoreHrmCandidateModal;
use App\Models\CoreHrmJobPostingModal;
use App\Models\CoreHrmEmployeeModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CoreHrmInterviewController extends Controller
{
  /**
   * Display a listing of interviews.
   */
  public function index(Request $request)
  {
    $query = CoreHrmInterviewModal::with(['jobPosting', 'candidate', 'interviewer']);

    // Apply filters
    if ($request->has('job_posting_id')) {
      $query->where('job_posting_id', $request->job_posting_id);
    }

    if ($request->has('candidate_id')) {
      $query->where('candidate_id', $request->candidate_id);
    }

    if ($request->has('interviewer_id')) {
      $query->where('interviewer_id', $request->interviewer_id);
    }

    if ($request->has('interview_type')) {
      $query->where('interview_type', $request->interview_type);
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
      $query->whereHas('candidate', function ($q) use ($search) {
        $q->where('first_name', 'like', "%{$search}%")
          ->orWhere('last_name', 'like', "%{$search}%");
      })->orWhereHas('interviewer', function ($q) use ($search) {
        $q->where('first_name', 'like', "%{$search}%")
          ->orWhere('last_name', 'like', "%{$search}%");
      });
    }

    // Sort
    $sortField = $request->input('sort_field', 'scheduled_date');
    $sortDirection = $request->input('sort_direction', 'desc');
    $query->orderBy($sortField, $sortDirection);

    $interviews = $query->paginate(10);

    return view('core.hrm.interviews.index', [
      'interviews' => $interviews,
      'jobPostings' => CoreHrmJobPostingModal::all(),
      'interviewTypes' => CoreHrmInterviewModal::getInterviewTypes(),
      'statuses' => CoreHrmInterviewModal::getStatuses()
    ]);
  }

  /**
   * Show the form for creating a new interview.
   */
  public function create(Request $request)
  {
    $candidate = null;
    $jobPosting = null;

    if ($request->has('candidate_id')) {
      $candidate = CoreHrmCandidateModal::findOrFail($request->candidate_id);
      $jobPosting = $candidate->jobPosting;
    }

    return view('core.hrm.interviews.create', [
      'candidate' => $candidate,
      'jobPosting' => $jobPosting,
      'candidates' => CoreHrmCandidateModal::whereIn('status', ['applied', 'screening', 'interviewing'])->get(),
      'interviewers' => CoreHrmEmployeeModal::active()->get(),
      'interviewTypes' => CoreHrmInterviewModal::getInterviewTypes()
    ]);
  }

  /**
   * Store a newly created interview.
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'job_posting_id' => 'required|exists:hrm_job_postings,id',
      'candidate_id' => 'required|exists:hrm_candidates,id',
      'interviewer_id' => 'required|exists:hrm_employees,id',
      'interview_type' => 'required|string',
      'round_number' => 'required|integer|min:1',
      'scheduled_date' => 'required|date',
      'scheduled_time' => 'required|date_format:Y-m-d H:i:s',
      'duration_minutes' => 'required|integer|min:1',
      'location' => 'nullable|string|max:255',
      'meeting_link' => 'nullable|url',
      'meeting_id' => 'nullable|string|max:255',
      'meeting_password' => 'nullable|string|max:255',
      'preparation_notes' => 'nullable|string',
      'questions' => 'nullable|array',
      'evaluation_criteria' => 'nullable|array'
    ]);

    if ($validator->fails()) {
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }

    $interview = new CoreHrmInterviewModal($request->all());
    $interview->created_by = Auth::id();
    $interview->save();

    return redirect()
      ->route('interviews.show', $interview)
      ->with('success', 'Interview scheduled successfully.');
  }

  /**
   * Display the specified interview.
   */
  public function show(CoreHrmInterviewModal $interview)
  {
    $interview->load(['jobPosting', 'candidate', 'interviewer', 'creator']);

    return view('core.hrm.interviews.show', [
      'interview' => $interview
    ]);
  }

  /**
   * Show the form for editing the specified interview.
   */
  public function edit(CoreHrmInterviewModal $interview)
  {
    return view('core.hrm.interviews.edit', [
      'interview' => $interview,
      'candidates' => CoreHrmCandidateModal::whereIn('status', ['applied', 'screening', 'interviewing'])->get(),
      'interviewers' => CoreHrmEmployeeModal::active()->get(),
      'interviewTypes' => CoreHrmInterviewModal::getInterviewTypes(),
      'statuses' => CoreHrmInterviewModal::getStatuses()
    ]);
  }

  /**
   * Update the specified interview.
   */
  public function update(Request $request, CoreHrmInterviewModal $interview)
  {
    $validator = Validator::make($request->all(), [
      'job_posting_id' => 'required|exists:hrm_job_postings,id',
      'candidate_id' => 'required|exists:hrm_candidates,id',
      'interviewer_id' => 'required|exists:hrm_employees,id',
      'interview_type' => 'required|string',
      'round_number' => 'required|integer|min:1',
      'scheduled_date' => 'required|date',
      'scheduled_time' => 'required|date_format:Y-m-d H:i:s',
      'duration_minutes' => 'required|integer|min:1',
      'location' => 'nullable|string|max:255',
      'meeting_link' => 'nullable|url',
      'meeting_id' => 'nullable|string|max:255',
      'meeting_password' => 'nullable|string|max:255',
      'preparation_notes' => 'nullable|string',
      'questions' => 'nullable|array',
      'evaluation_criteria' => 'nullable|array',
      'technical_skills_rating' => 'nullable|numeric|min:1|max:5',
      'soft_skills_rating' => 'nullable|numeric|min:1|max:5',
      'cultural_fit_rating' => 'nullable|numeric|min:1|max:5',
      'overall_rating' => 'nullable|numeric|min:1|max:5',
      'strengths' => 'nullable|array',
      'weaknesses' => 'nullable|array',
      'interviewer_notes' => 'nullable|string',
      'candidate_feedback' => 'nullable|string',
      'next_steps' => 'nullable|string',
      'status' => 'required|string',
      'cancellation_reason' => 'nullable|string|required_if:status,cancelled'
    ]);

    if ($validator->fails()) {
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }

    $interview->update($request->all());

    return redirect()
      ->route('interviews.show', $interview)
      ->with('success', 'Interview updated successfully.');
  }

  /**
   * Remove the specified interview.
   */
  public function destroy(CoreHrmInterviewModal $interview)
  {
    $interview->delete();

    return redirect()
      ->route('interviews.index')
      ->with('success', 'Interview deleted successfully.');
  }

  /**
   * Display the interview calendar.
   */
  public function calendar(Request $request)
  {
    $interviews = CoreHrmInterviewModal::with(['candidate', 'interviewer'])
      ->whereIn('status', ['scheduled', 'in_progress'])
      ->get()
      ->map(function ($interview) {
        return [
          'id' => $interview->id,
          'title' => $interview->candidate->full_name . ' - ' . $interview->interview_type,
          'start' => $interview->scheduled_time,
          'end' => $interview->scheduled_time->addMinutes($interview->duration_minutes),
          'url' => route('interviews.show', $interview)
        ];
      });

    return view('core.hrm.interviews.calendar', [
      'interviews' => $interviews
    ]);
  }
}
