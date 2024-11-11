<?php

namespace App\Http\Controllers;

use App\Models\TimeRegistration;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TimeReportController extends Controller
{
  public function index()
  {
    $summaryStats = $this->getSummaryStats();
    $recentReports = $this->getRecentReports();

    return view('time-registration.reports.index', compact(
      'summaryStats',
      'recentReports'
    ));
  }

  protected function getSummaryStats()
  {
    $currentMonth = [now()->startOfMonth(), now()->endOfMonth()];
    $lastMonth = [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()];

    return [
      'current_month' => [
        'total_hours' => TimeRegistration::whereBetween('date', $currentMonth)
          ->where('status', 'approved')
          ->sum('hours'),
        'billable_hours' => TimeRegistration::whereBetween('date', $currentMonth)
          ->where('status', 'approved')
          ->where('billable', true)
          ->sum('hours'),
        'projects_count' => TimeRegistration::whereBetween('date', $currentMonth)
          ->where('status', 'approved')
          ->distinct('project_id')
          ->count('project_id')
      ],
      'last_month' => [
        'total_hours' => TimeRegistration::whereBetween('date', $lastMonth)
          ->where('status', 'approved')
          ->sum('hours'),
        'billable_hours' => TimeRegistration::whereBetween('date', $lastMonth)
          ->where('status', 'approved')
          ->where('billable', true)
          ->sum('hours'),
        'projects_count' => TimeRegistration::whereBetween('date', $lastMonth)
          ->where('status', 'approved')
          ->distinct('project_id')
          ->count('project_id')
      ]
    ];
  }

  protected function getRecentReports()
  {
    return TimeRegistration::with(['project', 'task', 'user'])
      ->where('status', 'approved')
      ->orderBy('date', 'desc')
      ->limit(10)
      ->get()
      ->groupBy('date');
  }

  public function projectReport(Request $request)
  {
    $dateRange = $this->getDateRange($request);

    $projectStats = Project::withCount(['timeRegistrations' => function ($query) use ($dateRange) {
      $query->whereBetween('date', $dateRange)
        ->where('status', 'approved');
    }])
      ->withSum(['timeRegistrations' => function ($query) use ($dateRange) {
        $query->whereBetween('date', $dateRange)
          ->where('status', 'approved');
      }], 'hours')
      ->having('time_registrations_count', '>', 0)
      ->orderByDesc('time_registrations_sum_hours')
      ->paginate(15);

    return view('time-registration.reports.projects', compact('projectStats', 'dateRange'));
  }

  public function projectDetail(Request $request, Project $project)
  {
    $dateRange = $this->getDateRange($request);

    $timeRegistrations = TimeRegistration::with(['task', 'user'])
      ->where('project_id', $project->id)
      ->whereBetween('date', $dateRange)
      ->where('status', 'approved')
      ->orderBy('date', 'desc')
      ->get();

    $taskBreakdown = $timeRegistrations->groupBy('task_id')
      ->map(function ($group) {
        return [
          'task' => $group->first()->task,
          'total_hours' => $group->sum('hours'),
          'entries_count' => $group->count()
        ];
      });

    $userBreakdown = $timeRegistrations->groupBy('user_id')
      ->map(function ($group) {
        return [
          'user' => $group->first()->user,
          'total_hours' => $group->sum('hours'),
          'entries_count' => $group->count()
        ];
      });

    return view('time-registration.reports.project-detail', compact(
      'project',
      'timeRegistrations',
      'taskBreakdown',
      'userBreakdown',
      'dateRange'
    ));
  }

  public function userReport(Request $request)
  {
    $dateRange = $this->getDateRange($request);

    $userStats = User::withCount(['timeRegistrations' => function ($query) use ($dateRange) {
      $query->whereBetween('date', $dateRange)
        ->where('status', 'approved');
    }])
      ->withSum(['timeRegistrations' => function ($query) use ($dateRange) {
        $query->whereBetween('date', $dateRange)
          ->where('status', 'approved');
      }], 'hours')
      ->having('time_registrations_count', '>', 0)
      ->orderByDesc('time_registrations_sum_hours')
      ->paginate(15);

    return view('time-registration.reports.users', compact('userStats', 'dateRange'));
  }

  public function userDetail(Request $request, User $user)
  {
    $dateRange = $this->getDateRange($request);

    $timeRegistrations = TimeRegistration::with(['project', 'task'])
      ->where('user_id', $user->id)
      ->whereBetween('date', $dateRange)
      ->where('status', 'approved')
      ->orderBy('date', 'desc')
      ->get();

    $projectBreakdown = $timeRegistrations->groupBy('project_id')
      ->map(function ($group) {
        return [
          'project' => $group->first()->project,
          'total_hours' => $group->sum('hours'),
          'billable_hours' => $group->where('billable', true)->sum('hours')
        ];
      });

    $weeklyBreakdown = $timeRegistrations->groupBy(function ($item) {
      return $item->date->format('W');
    });

    return view('time-registration.reports.user-detail', compact(
      'user',
      'timeRegistrations',
      'projectBreakdown',
      'weeklyBreakdown',
      'dateRange'
    ));
  }

  public function taskReport(Request $request)
  {
    $dateRange = $this->getDateRange($request);

    $taskStats = Task::withCount(['timeRegistrations' => function ($query) use ($dateRange) {
      $query->whereBetween('date', $dateRange)
        ->where('status', 'approved');
    }])
      ->withSum(['timeRegistrations' => function ($query) use ($dateRange) {
        $query->whereBetween('date', $dateRange)
          ->where('status', 'approved');
      }], 'hours')
      ->having('time_registrations_count', '>', 0)
      ->orderByDesc('time_registrations_sum_hours')
      ->paginate(15);

    return view('time-registration.reports.tasks', compact('taskStats', 'dateRange'));
  }

  public function taskDetail(Request $request, Task $task)
  {
    $dateRange = $this->getDateRange($request);

    $timeRegistrations = TimeRegistration::with(['project', 'user'])
      ->where('task_id', $task->id)
      ->whereBetween('date', $dateRange)
      ->where('status', 'approved')
      ->orderBy('date', 'desc')
      ->get();

    $userBreakdown = $timeRegistrations->groupBy('user_id')
      ->map(function ($group) {
        return [
          'user' => $group->first()->user,
          'total_hours' => $group->sum('hours'),
          'entries_count' => $group->count()
        ];
      });

    return view('time-registration.reports.task-detail', compact(
      'task',
      'timeRegistrations',
      'userBreakdown',
      'dateRange'
    ));
  }

  public function customReport()
  {
    $projects = Project::select('id', 'name')->get();
    $users = User::select('id', 'name')->get();

    return view('time-registration.reports.custom', compact('projects', 'users'));
  }

  public function generateCustomReport(Request $request)
  {
    $validated = $request->validate([
      'date_from' => 'required|date',
      'date_to' => 'required|date|after_or_equal:date_from',
      'projects' => 'array',
      'projects.*' => 'exists:projects,id',
      'users' => 'array',
      'users.*' => 'exists:users,id',
      'group_by' => 'required|in:day,week,month,project,user',
      'include_billable_only' => 'boolean'
    ]);

    $query = TimeRegistration::with(['project', 'task', 'user'])
      ->whereBetween('date', [$validated['date_from'], $validated['date_to']])
      ->where('status', 'approved');

    if (!empty($validated['projects'])) {
      $query->whereIn('project_id', $validated['projects']);
    }

    if (!empty($validated['users'])) {
      $query->whereIn('user_id', $validated['users']);
    }

    if ($validated['include_billable_only']) {
      $query->where('billable', true);
    }

    $timeRegistrations = $query->get();

    $groupedData = $this->groupReportData(
      $timeRegistrations,
      $validated['group_by']
    );

    return view('time-registration.reports.custom-result', compact(
      'groupedData',
      'validated'
    ));
  }

  protected function groupReportData($timeRegistrations, $groupBy)
  {
    switch ($groupBy) {
      case 'day':
        return $timeRegistrations->groupBy(function ($item) {
          return $item->date->format('Y-m-d');
        });
      case 'week':
        return $timeRegistrations->groupBy(function ($item) {
          return $item->date->format('W');
        });
      case 'month':
        return $timeRegistrations->groupBy(function ($item) {
          return $item->date->format('Y-m');
        });
      case 'project':
        return $timeRegistrations->groupBy('project_id');
      case 'user':
        return $timeRegistrations->groupBy('user_id');
      default:
        return $timeRegistrations;
    }
  }

  protected function getDateRange(Request $request)
  {
    $from = $request->date_from ? Carbon::parse($request->date_from) : now()->startOfMonth();
    $to = $request->date_to ? Carbon::parse($request->date_to) : now()->endOfMonth();

    return [$from, $to];
  }

  public function exportReport(Request $request, $type)
  {
    $dateRange = $this->getDateRange($request);

    switch ($type) {
      case 'project':
        $data = $this->generateProjectExport($dateRange);
        $filename = 'project-time-report.csv';
        break;
      case 'user':
        $data = $this->generateUserExport($dateRange);
        $filename = 'user-time-report.csv';
        break;
      case 'task':
        $data = $this->generateTaskExport($dateRange);
        $filename = 'task-time-report.csv';
        break;
      default:
        abort(404);
    }

    return response()->streamDownload(function () use ($data) {
      $output = fopen('php://output', 'w');

      // Headers
      fputcsv($output, array_keys($data[0]));

      // Data
      foreach ($data as $row) {
        fputcsv($output, $row);
      }

      fclose($output);
    }, $filename);
  }

  protected function generateProjectExport($dateRange)
  {
    return Project::withCount(['timeRegistrations' => function ($query) use ($dateRange) {
      $query->whereBetween('date', $dateRange)
        ->where('status', 'approved');
    }])
      ->withSum(['timeRegistrations' => function ($query) use ($dateRange) {
        $query->whereBetween('date', $dateRange)
          ->where('status', 'approved');
      }], 'hours')
      ->having('time_registrations_count', '>', 0)
      ->get()
      ->map(function ($project) {
        return [
          'Project Name' => $project->name,
          'Total Hours' => $project->time_registrations_sum_hours,
          'Total Entries' => $project->time_registrations_count,
          'Average Hours Per Entry' => round($project->time_registrations_sum_hours / $project->time_registrations_count, 2)
        ];
      })
      ->toArray();
  }

  protected function generateUserExport($dateRange)
  {
    return User::withCount(['timeRegistrations' => function ($query) use ($dateRange) {
      $query->whereBetween('date', $dateRange)
        ->where('status', 'approved');
    }])
      ->withSum(['timeRegistrations' => function ($query) use ($dateRange) {
        $query->whereBetween('date', $dateRange)
          ->where('status', 'approved');
      }], 'hours')
      ->having('time_registrations_count', '>', 0)
      ->get()
      ->map(function ($user) {
        return [
          'User Name' => $user->name,
          'Total Hours' => $user->time_registrations_sum_hours,
          'Total Entries' => $user->time_registrations_count,
          'Average Hours Per Entry' => round($user->time_registrations_sum_hours / $user->time_registrations_count, 2)
        ];
      })
      ->toArray();
  }

  protected function generateTaskExport($dateRange)
  {
    return Task::withCount(['timeRegistrations' => function ($query) use ($dateRange) {
      $query->whereBetween('date', $dateRange)
        ->where('status', 'approved');
    }])
      ->withSum(['timeRegistrations' => function ($query) use ($dateRange) {
        $query->whereBetween('date', $dateRange)
          ->where('status', 'approved');
      }], 'hours')
      ->having('time_registrations_count', '>', 0)
      ->get()
      ->map(function ($task) {
        return [
          'Task Name' => $task->name,
          'Project' => $task->project->name,
          'Total Hours' => $task->time_registrations_sum_hours,
          'Total Entries' => $task->time_registrations_count,
          'Average Hours Per Entry' => round($task->time_registrations_sum_hours / $task->time_registrations_count, 2)
        ];
      })
      ->toArray();
  }
}
