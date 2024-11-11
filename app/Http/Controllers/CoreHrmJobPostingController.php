<?php

namespace App\Http\Controllers;

use App\Models\CoreHrmJobPostingModal;
use App\Models\CoreHrmDepartmentModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CoreHrmJobPostingController extends Controller
{
  /**
   * Display a listing of job postings.
   */
  public function index(Request $request)
  {
    $query = CoreHrmJobPostingModal::with(['department', 'candidates']);

    // Apply filters
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    if ($request->has('department_id')) {
      $query->where('department_id', $request->department_id);
    }

    if ($request->has('position_type')) {
      $query->where('position_type', $request->position_type);
    }

    if ($request->has('experience_level')) {
      $query->where('experience_level', $request->experience_level);
    }

    if ($request->has('location_type')) {
      $query->where('location_type', $request->location_type);
    }

    // Search
    if ($request->has('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('title', 'like', "%{$search}%")
          ->orWhere('description', 'like', "%{$search}%")
          ->orWhere('requirements', 'like', "%{$search}%")
          ->orWhere('responsibilities', 'like', "%{$search}%");
      });
    }

    // Sort
    $sortField = $request->input('sort_field', 'posting_date');
    $sortDirection = $request->input('sort_direction', 'desc');
    $query->orderBy($sortField, $sortDirection);

    $jobPostings = $query->paginate(10);

    return view('core.hrm.job-postings.index', [
      'jobPostings' => $jobPostings,
      'departments' => CoreHrmDepartmentModal::all(),
      'positionTypes' => CoreHrmJobPostingModal::getPositionTypes(),
      'experienceLevels' => CoreHrmJobPostingModal::getExperienceLevels(),
      'locationTypes' => CoreHrmJobPostingModal::getLocationTypes(),
      'statuses' => CoreHrmJobPostingModal::getStatuses()
    ]);
  }

  /**
   * Show the form for creating a new job posting.
   */
  public function create()
  {
    return view('core.hrm.job-postings.create', [
      'departments' => CoreHrmDepartmentModal::all(),
      'positionTypes' => CoreHrmJobPostingModal::getPositionTypes(),
      'experienceLevels' => CoreHrmJobPostingModal::getExperienceLevels(),
      'locationTypes' => CoreHrmJobPostingModal::getLocationTypes(),
      'salaryPeriods' => CoreHrmJobPostingModal::getSalaryPeriods()
    ]);
  }

  /**
   * Store a newly created job posting.
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string|max:255',
      'department_id' => 'required|exists:hrm_departments,id',
      'position_type' => 'required|string',
      'experience_level' => 'required|string',
      'location_type' => 'required|string',
      'location' => 'nullable|string|max:255',
      'salary_min' => 'nullable|numeric|min:0',
      'salary_max' => 'nullable|numeric|min:0|gt:salary_min',
      'salary_currency' => 'required|string|size:3',
      'salary_period' => 'required|string',
      'description' => 'required|string',
      'requirements' => 'nullable|string',
      'responsibilities' => 'nullable|string',
      'qualifications' => 'nullable|string',
      'benefits' => 'nullable|string',
      'skills_required' => 'nullable|array',
      'number_of_positions' => 'required|integer|min:1',
      'application_deadline' => 'nullable|date|after:today',
      'posting_date' => 'required|date',
      'status' => 'required|string'
    ]);

    if ($validator->fails()) {
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }

    $jobPosting = new CoreHrmJobPostingModal($request->all());
    $jobPosting->created_by = Auth::id();
    $jobPosting->save();

    return redirect()
      ->route('job-postings.show', $jobPosting)
      ->with('success', 'Job posting created successfully.');
  }

  /**
   * Display the specified job posting.
   */
  public function show(CoreHrmJobPostingModal $jobPosting)
  {
    $jobPosting->load(['department', 'candidates', 'creator']);

    return view('core.hrm.job-postings.show', [
      'jobPosting' => $jobPosting
    ]);
  }

  /**
   * Show the form for editing the specified job posting.
   */
  public function edit(CoreHrmJobPostingModal $jobPosting)
  {
    return view('core.hrm.job-postings.edit', [
      'jobPosting' => $jobPosting,
      'departments' => CoreHrmDepartmentModal::all(),
      'positionTypes' => CoreHrmJobPostingModal::getPositionTypes(),
      'experienceLevels' => CoreHrmJobPostingModal::getExperienceLevels(),
      'locationTypes' => CoreHrmJobPostingModal::getLocationTypes(),
      'salaryPeriods' => CoreHrmJobPostingModal::getSalaryPeriods(),
      'statuses' => CoreHrmJobPostingModal::getStatuses()
    ]);
  }

  /**
   * Update the specified job posting.
   */
  public function update(Request $request, CoreHrmJobPostingModal $jobPosting)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string|max:255',
      'department_id' => 'required|exists:hrm_departments,id',
      'position_type' => 'required|string',
      'experience_level' => 'required|string',
      'location_type' => 'required|string',
      'location' => 'nullable|string|max:255',
      'salary_min' => 'nullable|numeric|min:0',
      'salary_max' => 'nullable|numeric|min:0|gt:salary_min',
      'salary_currency' => 'required|string|size:3',
      'salary_period' => 'required|string',
      'description' => 'required|string',
      'requirements' => 'nullable|string',
      'responsibilities' => 'nullable|string',
      'qualifications' => 'nullable|string',
      'benefits' => 'nullable|string',
      'skills_required' => 'nullable|array',
      'number_of_positions' => 'required|integer|min:1',
      'application_deadline' => 'nullable|date',
      'posting_date' => 'required|date',
      'status' => 'required|string'
    ]);

    if ($validator->fails()) {
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }

    $jobPosting->update($request->all());

    return redirect()
      ->route('job-postings.show', $jobPosting)
      ->with('success', 'Job posting updated successfully.');
  }

  /**
   * Remove the specified job posting.
   */
  public function destroy(CoreHrmJobPostingModal $jobPosting)
  {
    $jobPosting->delete();

    return redirect()
      ->route('job-postings.index')
      ->with('success', 'Job posting deleted successfully.');
  }

  /**
   * Display the job posting dashboard.
   */
  public function dashboard()
  {
    $stats = [
      'total' => CoreHrmJobPostingModal::count(),
      'active' => CoreHrmJobPostingModal::active()->count(),
      'closed' => CoreHrmJobPostingModal::closed()->count(),
      'draft' => CoreHrmJobPostingModal::where('status', 'draft')->count(),
      'on_hold' => CoreHrmJobPostingModal::where('status', 'on-hold')->count(),
      'total_positions' => CoreHrmJobPostingModal::sum('number_of_positions'),
      'total_applications' => CoreHrmJobPostingModal::withCount('candidates')
        ->get()
        ->sum('candidates_count'),
      'positions_by_department' => CoreHrmJobPostingModal::with('department')
        ->select('department_id')
        ->selectRaw('SUM(number_of_positions) as total_positions')
        ->groupBy('department_id')
        ->get(),
      'recent_postings' => CoreHrmJobPostingModal::with('department')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get()
    ];

    return view('core.hrm.job-postings.dashboard', [
      'stats' => $stats
    ]);
  }
}
