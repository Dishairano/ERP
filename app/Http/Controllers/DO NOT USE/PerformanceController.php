<?php

namespace App\Http\Controllers;

use App\Models\PerformanceReview;
use App\Models\PerformanceGoal;
use App\Models\PerformanceFeedback;
use App\Models\DevelopmentPlan;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
  /**
   * Display performance reviews.
   *
   * @return \Illuminate\View\View
   */
  public function reviews()
  {
    $reviews = PerformanceReview::with(['employee', 'reviewer'])
      ->latest()
      ->paginate(10);

    return view('performance.reviews', compact('reviews'));
  }

  /**
   * Display goals and KPIs.
   *
   * @return \Illuminate\View\View
   */
  public function goals()
  {
    $goals = PerformanceGoal::with(['employee', 'approver'])
      ->latest()
      ->paginate(10);

    return view('performance.goals', compact('goals'));
  }

  /**
   * Display 360° feedback.
   *
   * @return \Illuminate\View\View
   */
  public function feedback()
  {
    $feedbacks = PerformanceFeedback::with(['employee', 'reviewer'])
      ->latest()
      ->paginate(10);

    return view('performance.feedback', compact('feedbacks'));
  }

  /**
   * Display development plans.
   *
   * @return \Illuminate\View\View
   */
  public function development()
  {
    $plans = DevelopmentPlan::with(['employee', 'mentor'])
      ->latest()
      ->paginate(10);

    return view('performance.development', compact('plans'));
  }

  /**
   * Store a new performance review.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeReview(Request $request)
  {
    $validated = $request->validate([
      'employee_id' => 'required|exists:employees,id',
      'review_period' => 'required|string',
      'review_date' => 'required|date',
      'ratings' => 'required|array',
      'ratings.*' => 'required|numeric|min:1|max:5',
      'strengths' => 'required|array',
      'areas_for_improvement' => 'required|array',
      'goals' => 'required|array',
      'comments' => 'nullable|string'
    ]);

    PerformanceReview::create([
      ...$validated,
      'reviewer_id' => auth()->id(),
      'status' => 'draft'
    ]);

    return redirect()->route('performance.reviews')
      ->with('success', 'Performance review created successfully.');
  }

  /**
   * Store a new performance goal.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeGoal(Request $request)
  {
    $validated = $request->validate([
      'employee_id' => 'required|exists:employees,id',
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'category' => 'required|string',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'metrics' => 'required|array',
      'target' => 'required|string',
      'priority' => 'required|in:low,medium,high'
    ]);

    PerformanceGoal::create([
      ...$validated,
      'status' => 'pending',
      'created_by' => auth()->id()
    ]);

    return redirect()->route('performance.goals')
      ->with('success', 'Performance goal created successfully.');
  }

  /**
   * Store a new 360° feedback.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeFeedback(Request $request)
  {
    $validated = $request->validate([
      'employee_id' => 'required|exists:employees,id',
      'relationship' => 'required|string',
      'period' => 'required|string',
      'competencies' => 'required|array',
      'competencies.*.category' => 'required|string',
      'competencies.*.rating' => 'required|numeric|min:1|max:5',
      'competencies.*.comments' => 'required|string',
      'strengths' => 'required|array',
      'improvements' => 'required|array',
      'comments' => 'nullable|string'
    ]);

    PerformanceFeedback::create([
      ...$validated,
      'reviewer_id' => auth()->id(),
      'status' => 'submitted'
    ]);

    return redirect()->route('performance.feedback')
      ->with('success', '360° feedback submitted successfully.');
  }

  /**
   * Store a new development plan.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeDevelopmentPlan(Request $request)
  {
    $validated = $request->validate([
      'employee_id' => 'required|exists:employees,id',
      'mentor_id' => 'required|exists:employees,id',
      'objectives' => 'required|array',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'activities' => 'required|array',
      'resources' => 'required|array',
      'milestones' => 'required|array',
      'success_criteria' => 'required|array',
      'notes' => 'nullable|string'
    ]);

    DevelopmentPlan::create([
      ...$validated,
      'status' => 'draft',
      'created_by' => auth()->id()
    ]);

    return redirect()->route('performance.development')
      ->with('success', 'Development plan created successfully.');
  }
}
