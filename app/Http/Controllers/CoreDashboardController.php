<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CoreProjectModal;
use App\Models\CoreProjectTaskModal;
use App\Models\CoreHrmEmployeeModal;
use App\Models\CoreHrmDepartmentModal;
use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoreDashboardController extends Controller
{
    public function index()
    {
        try {
            $GLOBALS['menuData'] = Helper::initMenu();

            return view('content.dashboard.dashboard', [
                'totalRevenue' => $this->getTotalRevenue(),
                'activeProjects' => $this->getActiveProjects(),
                'teamMembers' => $this->getTeamMembers(),
                'pendingTasks' => $this->getPendingTasks(),
                'projectTimeline' => $this->getProjectTimeline(),
                'projectStats' => $this->getProjectStats(),
                'departmentStats' => $this->getDepartmentStats(),
                'menuData' => $GLOBALS['menuData']
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard Error: ' . $e->getMessage());
            return view('content.dashboard.dashboard', [
                'error' => 'Error loading dashboard data',
                'menuData' => Helper::initMenu()
            ]);
        }
    }

    public function refreshMetrics(): JsonResponse
    {
        try {
            return response()->json([
                'totalRevenue' => $this->getTotalRevenue(),
                'activeProjects' => $this->getActiveProjects(),
                'teamMembers' => $this->getTeamMembers(),
                'pendingTasks' => $this->getPendingTasks(),
                'projectTimeline' => $this->getProjectTimeline(),
                'projectStats' => $this->getProjectStats(),
                'departmentStats' => $this->getDepartmentStats()
            ]);
        } catch (\Exception $e) {
            Log::error('Refresh Metrics Error: ' . $e->getMessage());
            return response()->json(['error' => 'Error refreshing metrics'], 500);
        }
    }

    private function getTotalRevenue(Carbon $startDate = null)
    {
        try {
            $query = CoreProjectModal::whereIn('status', ['in_progress', 'completed']);

            if ($startDate) {
                $query->where('start_date', '>=', $startDate);
            }

            return $query->sum('budget') ?? 0;
        } catch (\Exception $e) {
            Log::error('Get Total Revenue Error: ' . $e->getMessage());
            return 0;
        }
    }

    private function getActiveProjects()
    {
        try {
            return CoreProjectModal::where('status', 'in_progress')
                ->whereNull('deleted_at')
                ->count();
        } catch (\Exception $e) {
            Log::error('Get Active Projects Error: ' . $e->getMessage());
            return 0;
        }
    }

    private function getTeamMembers()
    {
        try {
            return CoreHrmEmployeeModal::where('is_active', true)
                ->whereNull('deleted_at')
                ->count();
        } catch (\Exception $e) {
            Log::error('Get Team Members Error: ' . $e->getMessage());
            return 0;
        }
    }

    private function getPendingTasks()
    {
        try {
            return CoreProjectTaskModal::whereIn('status', ['pending', 'in_progress'])
                ->whereNull('deleted_at')
                ->whereHas('project', function ($query) {
                    $query->where('status', 'in_progress');
                })
                ->count();
        } catch (\Exception $e) {
            Log::error('Get Pending Tasks Error: ' . $e->getMessage());
            return 0;
        }
    }

    private function getProjectTimeline(Carbon $startDate = null)
    {
        try {
            $query = CoreProjectModal::with(['tasks', 'manager', 'team'])
                ->where('status', '!=', 'cancelled')
                ->orderBy('updated_at', 'desc');

            if ($startDate) {
                $query->where('updated_at', '>=', $startDate);
            }

            $projects = $query->limit(5)->get();

            return $projects->map(function ($project) {
                $type = match($project->status) {
                    'completed' => 'success',
                    'in_progress' => 'info',
                    'on_hold' => 'warning',
                    'cancelled' => 'danger',
                    default => 'primary'
                };

                $description = match(true) {
                    $project->tasks()->where('status', 'completed')->exists() =>
                        'Latest task completed: ' . $project->tasks()->where('status', 'completed')
                            ->latest()->first()->title,
                    $project->tasks()->where('status', 'in_progress')->exists() =>
                        'Current task: ' . $project->tasks()->where('status', 'in_progress')
                            ->latest()->first()->title,
                    default => $project->description ?? 'No tasks yet'
                };

                return [
                    'title' => $project->name,
                    'description' => $description,
                    'date' => $project->updated_at->format('M d, Y'),
                    'type' => $type,
                    'team' => $project->team->map(function ($member) {
                        return [
                            'name' => $member->name,
                            'avatar' => $member->avatar,
                            'role' => $member->pivot->role ?? 'Team Member'
                        ];
                    })
                ];
            });
        } catch (\Exception $e) {
            Log::error('Get Project Timeline Error: ' . $e->getMessage());
            return collect([]);
        }
    }

    private function getProjectStats(Carbon $startDate = null)
    {
        try {
            $query = CoreProjectModal::with(['tasks', 'manager', 'team'])
                ->whereIn('status', ['in_progress', 'on_hold', 'in_review']);

            if ($startDate) {
                $query->where('updated_at', '>=', $startDate);
            }

            $projects = $query->get();

            return $projects->map(function ($project) {
                $previousProgress = $project->tasks()
                    ->where('updated_at', '<=', now()->subWeek())
                    ->avg('progress') ?? 0;

                $currentProgress = $project->progress;
                $change = $currentProgress - $previousProgress;

                return [
                    'name' => $project->name,
                    'status' => str_replace('_', ' ', ucfirst($project->status)),
                    'progress' => round($currentProgress),
                    'trend' => $change >= 0 ? 'success' : 'danger',
                    'change' => ($change >= 0 ? '+' : '') . number_format($change, 1) . '%',
                    'color' => match($project->status) {
                        'in_progress' => 'primary',
                        'on_hold' => 'warning',
                        'in_review' => 'info',
                        default => 'secondary'
                    },
                    'team' => $project->team->map(function ($member) {
                        return [
                            'name' => $member->name,
                            'avatar' => $member->avatar,
                            'role' => $member->pivot->role ?? 'Team Member'
                        ];
                    })
                ];
            });
        } catch (\Exception $e) {
            Log::error('Get Project Stats Error: ' . $e->getMessage());
            return collect([]);
        }
    }

    private function getDepartmentStats()
    {
        try {
            return CoreHrmDepartmentModal::with(['manager', 'employees'])
                ->whereNull('deleted_at')
                ->get()
                ->map(function ($department) {
                    $totalBudget = CoreProjectModal::whereHas('manager', function ($query) use ($department) {
                        $query->whereHas('employee', function ($q) use ($department) {
                            $q->where('department_id', $department->id);
                        });
                    })->sum('budget');

                    $projects = CoreProjectModal::whereHas('manager', function ($query) use ($department) {
                        $query->whereHas('employee', function ($q) use ($department) {
                            $q->where('department_id', $department->id);
                        });
                    })->get();

                    $progress = $projects->isEmpty() ? 0 : round($projects->avg('progress'));

                    return [
                        'name' => $department->name,
                        'manager' => $department->manager ? $department->manager->full_name : 'Not Assigned',
                        'budget' => $totalBudget,
                        'progress' => $progress,
                        'status' => $progress >= 70 ? 'Active' : ($progress >= 30 ? 'Progressing' : 'At Risk'),
                        'color' => match(true) {
                            $progress >= 70 => 'success',
                            $progress >= 30 => 'primary',
                            default => 'warning'
                        },
                        'icon' => match(strtolower($department->name)) {
                            'development' => 'code-line',
                            'marketing' => 'line-chart-line',
                            'hr', 'human resources' => 'team-line',
                            'finance' => 'money-dollar-circle-line',
                            default => 'building-line'
                        },
                        'status_color' => match(true) {
                            $progress >= 70 => 'success',
                            $progress >= 30 => 'info',
                            default => 'warning'
                        }
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Get Department Stats Error: ' . $e->getMessage());
            return collect([]);
        }
    }
}
