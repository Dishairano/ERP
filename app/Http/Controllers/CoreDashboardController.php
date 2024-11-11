<?php

namespace App\Http\Controllers;

use App\Models\CoreHrmJobPostingModal;
use App\Models\CoreHrmCandidateModal;
use App\Models\CoreHrmInterviewModal;
use App\Models\CoreHrmAssessmentModal;
use App\Models\CoreHrmDepartmentModal;
use App\Models\CoreProjectDashboardModal;
use App\Models\CoreProjectTaskModal;
use App\Models\CoreProjectRiskModal;
use App\Models\CoreProjectTemplateModal;
use App\Models\CoreHrmPerformanceReviewModal;
use App\Models\CoreLeaveRequestModal;
use App\Models\CoreTimeRegistrationModal;
use App\Models\CoreOvertimeRecordModal;
use App\Models\CoreFinanceBudgetModal;
use App\Models\CoreFinanceCashFlowModal;
use App\Models\CoreHrmTrainingRecordModal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CoreDashboardController extends Controller
{
  public function index()
  {
    // Get active job postings count and growth
    $activeJobPostings = CoreHrmJobPostingModal::active()->count();
    $lastMonthJobPostings = CoreHrmJobPostingModal::active()
      ->where('created_at', '<=', Carbon::now()->subMonth())
      ->count();
    $jobPostingsGrowth = $lastMonthJobPostings > 0
      ? round((($activeJobPostings - $lastMonthJobPostings) / $lastMonthJobPostings) * 100)
      : 0;

    // Get candidates count and growth
    $totalCandidates = CoreHrmCandidateModal::count();
    $lastMonthCandidates = CoreHrmCandidateModal::where('created_at', '<=', Carbon::now()->subMonth())
      ->count();
    $candidatesGrowth = $lastMonthCandidates > 0
      ? round((($totalCandidates - $lastMonthCandidates) / $lastMonthCandidates) * 100)
      : 0;

    // Get upcoming interviews count
    $upcomingInterviews = CoreHrmInterviewModal::upcoming()->count();

    // Get pending assessments count
    $pendingAssessments = CoreHrmAssessmentModal::pending()->count();

    // Get pipeline data for the last 7 days
    $pipelineData = [
      'applications' => $this->getPipelineData('applications'),
      'interviews' => $this->getPipelineData('interviews'),
      'offers' => $this->getPipelineData('offers')
    ];

    // Get interview distribution
    $interviewDistribution = [
      'labels' => ['Scheduled', 'Completed', 'Cancelled', 'No Show'],
      'values' => [
        CoreHrmInterviewModal::where('status', 'scheduled')->count(),
        CoreHrmInterviewModal::where('status', 'completed')->count(),
        CoreHrmInterviewModal::where('status', 'cancelled')->count(),
        CoreHrmInterviewModal::where('status', 'no_show')->count()
      ]
    ];

    // Get recent interviews
    $recentInterviews = CoreHrmInterviewModal::with(['candidate', 'jobPosting'])
      ->upcoming()
      ->limit(5)
      ->get()
      ->map(function ($interview) {
        return [
          'candidate_name' => $interview->candidate->full_name,
          'job_title' => $interview->jobPosting->title,
          'date' => $interview->scheduled_date->format('M d, Y'),
          'time' => $interview->scheduled_time->format('H:i'),
          'type' => $interview->interview_type,
          'duration' => $interview->formatted_duration
        ];
      });

    // Get recent applications
    $recentApplications = CoreHrmCandidateModal::with('jobPosting')
      ->orderBy('created_at', 'desc')
      ->limit(5)
      ->get()
      ->map(function ($candidate) {
        return [
          'name' => $candidate->full_name,
          'job_title' => $candidate->jobPosting->title,
          'status' => $candidate->status,
          'applied_date' => $candidate->created_at->format('M d, Y'),
          'experience' => $candidate->experience_years . ' years',
          'location' => $candidate->city . ', ' . $candidate->country
        ];
      });

    // Get department statistics with enhanced metrics
    $departments = CoreHrmDepartmentModal::active()->get();
    $departmentStats = $departments->map(function ($dept) {
      $employeeCount = $dept->getTotalEmployeeCount();
      $openPositions = $dept->jobPostings()->where('status', 'active')->count();

      // Get performance reviews for employees in this department
      $employeeIds = $dept->employees()->pluck('id');
      $pendingReviews = CoreHrmPerformanceReviewModal::whereIn('employee_id', $employeeIds)
        ->where('status', 'draft')
        ->count();

      // Get leave requests for employees in this department
      $approvedLeaves = CoreLeaveRequestModal::whereIn('employee_id', $employeeIds)
        ->where('status', 'approved')
        ->where('start_date', '>=', Carbon::now())
        ->count();

      // Get training completion rate
      $totalTrainings = CoreHrmTrainingRecordModal::whereIn('employee_id', $employeeIds)->count();
      $completedTrainings = CoreHrmTrainingRecordModal::whereIn('employee_id', $employeeIds)
        ->where('status', 'completed')
        ->count();
      $trainingCompletion = $totalTrainings > 0 ? round(($completedTrainings / $totalTrainings) * 100) : 0;

      // Get budget utilization
      $budgetAllocated = $dept->budget_allocated ?? 0;
      $budgetSpent = $dept->budget_spent ?? 0;
      $budgetUtilization = $budgetAllocated > 0 ? round(($budgetSpent / $budgetAllocated) * 100) : 0;

      return [
        'name' => $dept->name,
        'manager' => $dept->manager?->name,
        'employees' => $employeeCount,
        'open_positions' => $openPositions,
        'pending_reviews' => $pendingReviews,
        'approved_leaves' => $approvedLeaves,
        'budget_utilization' => $budgetUtilization,
        'training_completion' => $trainingCompletion,
        'total_trainings' => $totalTrainings,
        'completed_trainings' => $completedTrainings,
        'budget_allocated' => $budgetAllocated,
        'budget_spent' => $budgetSpent,
        'budget_remaining' => $budgetAllocated - $budgetSpent
      ];
    });

    // Get project statistics with detailed metrics
    $projectStats = CoreProjectDashboardModal::with(['project', 'tasks', 'risks'])
      ->get()
      ->map(function ($dashboard) {
        return [
          'name' => $dashboard->project->name,
          'progress' => $dashboard->progress_percentage,
          'tasks_completed' => $dashboard->completed_tasks,
          'tasks_total' => $dashboard->total_tasks,
          'budget_spent' => $dashboard->budget_spent,
          'budget_total' => $dashboard->budget_allocated,
          'status' => $dashboard->status,
          'priority' => $dashboard->priority,
          'upcoming_milestones' => $dashboard->upcoming_milestones_count,
          'risk_count' => $dashboard->active_risks,
          'high_priority_tasks' => $dashboard->high_priority_tasks,
          'overdue_tasks' => $dashboard->overdue_tasks,
          'team_members' => $dashboard->team_members_count,
          'last_updated' => $dashboard->updated_at->diffForHumans()
        ];
      });

    // Get performance review statistics
    $performanceStats = [
      'completed' => CoreHrmPerformanceReviewModal::where('status', 'completed')
        ->where('completed_date', '>=', Carbon::now()->startOfYear())
        ->count(),
      'pending' => CoreHrmPerformanceReviewModal::where('status', 'draft')->count(),
      'upcoming' => CoreHrmPerformanceReviewModal::where('status', 'in_progress')
        ->where('next_review_date', '>', Carbon::now())
        ->count(),
      'average_rating' => CoreHrmPerformanceReviewModal::where('status', 'completed')
        ->where('completed_date', '>=', Carbon::now()->startOfYear())
        ->avg('overall_rating') ?? 0
    ];

    // Get leave statistics
    $leaveStats = [
      'pending_requests' => CoreLeaveRequestModal::where('status', 'pending')->count(),
      'approved_upcoming' => CoreLeaveRequestModal::where('status', 'approved')
        ->where('start_date', '>', Carbon::now())
        ->count(),
      'on_leave_today' => CoreLeaveRequestModal::where('status', 'approved')
        ->whereDate('start_date', '<=', Carbon::now())
        ->whereDate('end_date', '>=', Carbon::now())
        ->count(),
      'leave_distribution' => [
        'vacation' => CoreLeaveRequestModal::join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
          ->where('leave_types.code', 'vacation')
          ->count(),
        'sick' => CoreLeaveRequestModal::join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
          ->where('leave_types.code', 'sick')
          ->count(),
        'personal' => CoreLeaveRequestModal::join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
          ->where('leave_types.code', 'personal')
          ->count(),
        'other' => CoreLeaveRequestModal::join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
          ->whereNotIn('leave_types.code', ['vacation', 'sick', 'personal'])
          ->count()
      ]
    ];

    // Get time tracking statistics
    $timeStats = [
      'total_hours_this_week' => CoreTimeRegistrationModal::whereBetween('date', [
        Carbon::now()->startOfWeek(),
        Carbon::now()->endOfWeek()
      ])->sum('hours'),
      'total_hours_this_month' => CoreTimeRegistrationModal::whereBetween('date', [
        Carbon::now()->startOfMonth(),
        Carbon::now()->endOfMonth()
      ])->sum('hours'),
      'overtime_hours' => CoreOvertimeRecordModal::whereBetween('date', [
        Carbon::now()->startOfMonth(),
        Carbon::now()->endOfMonth()
      ])->where('status', 'approved')->sum('hours'),
      'project_distribution' => CoreTimeRegistrationModal::with('project')
        ->whereBetween('date', [
          Carbon::now()->startOfMonth(),
          Carbon::now()->endOfMonth()
        ])
        ->get()
        ->groupBy('project.name')
        ->map(function ($entries) {
          return $entries->sum('hours');
        })
    ];

    // Get financial statistics
    $financialStats = [
      'total_budget' => CoreFinanceBudgetModal::sum('amount'),
      'total_spent' => CoreFinanceBudgetModal::sum('spent'),
      'cash_flow' => CoreFinanceCashFlowModal::whereBetween('date', [
        Carbon::now()->startOfMonth(),
        Carbon::now()->endOfMonth()
      ])->sum('amount'),
      'budget_variance' => CoreFinanceBudgetModal::selectRaw('SUM(amount - spent) as variance')->value('variance') ?? 0
    ];

    return view('content.dashboard.dashboard', compact(
      'activeJobPostings',
      'jobPostingsGrowth',
      'totalCandidates',
      'candidatesGrowth',
      'upcomingInterviews',
      'pendingAssessments',
      'pipelineData',
      'interviewDistribution',
      'recentInterviews',
      'recentApplications',
      'departmentStats',
      'projectStats',
      'performanceStats',
      'leaveStats',
      'timeStats',
      'financialStats'
    ));
  }

  private function getPipelineData($type)
  {
    $data = [];
    for ($i = 6; $i >= 0; $i--) {
      $date = Carbon::now()->subDays($i);
      switch ($type) {
        case 'applications':
          $count = CoreHrmCandidateModal::whereDate('created_at', $date)->count();
          break;
        case 'interviews':
          $count = CoreHrmInterviewModal::whereDate('scheduled_date', $date)->count();
          break;
        case 'offers':
          $count = CoreHrmCandidateModal::where('status', 'offered')
            ->whereDate('updated_at', $date)
            ->count();
          break;
        default:
          $count = 0;
      }
      $data[] = [
        'date' => $date->format('M d'),
        'count' => $count
      ];
    }
    return $data;
  }
}
