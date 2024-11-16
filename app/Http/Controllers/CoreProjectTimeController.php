<?php

namespace App\Http\Controllers;

use App\Models\CoreProjectModal;
use App\Models\TimeRegistrationModal;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CoreProjectTimeController extends Controller
{
    /**
     * Display the time tracking page for a project.
     */
    public function tracking(Request $request, CoreProjectModal $project)
    {
        // Get project team members through task assignments and project manager
        $teamMembers = User::whereHas('tasks', function ($query) use ($project) {
            $query->where('project_id', $project->id)
                  ->whereNull('deleted_at');
        })->orWhere('id', $project->manager_id)
          ->distinct()
          ->get();

        // Build time entries query
        $query = TimeRegistrationModal::where('project_id', $project->id)
            ->with(['user', 'task']);

        // Apply date filters
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('date', [
                Carbon::parse($request->start_date),
                Carbon::parse($request->end_date)
            ]);
        }

        // Apply user filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Get time entries with pagination
        $timeEntries = $query->orderBy('date', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Calculate statistics
        $statsQuery = TimeRegistrationModal::where('project_id', $project->id);

        // Apply date filters to stats if present
        if ($request->filled(['start_date', 'end_date'])) {
            $statsQuery->whereBetween('date', [
                Carbon::parse($request->start_date),
                Carbon::parse($request->end_date)
            ]);
        }

        // Apply user filter to stats if present
        if ($request->filled('user_id')) {
            $statsQuery->where('user_id', $request->user_id);
        }

        // Clone query for different stats to avoid query builder modifications
        $totalHours = $statsQuery->sum('hours');
        $billableHours = (clone $statsQuery)->where('billable', true)->sum('hours');
        $overtimeHours = (clone $statsQuery)->where('overtime', true)->sum('hours');
        $teamMembersCount = $teamMembers->count();

        return view('content.project-time.tracking', compact(
            'project',
            'timeEntries',
            'teamMembers',
            'totalHours',
            'billableHours',
            'overtimeHours',
            'teamMembersCount'
        ));
    }

    /**
     * Get project time statistics via API.
     */
    public function getStats(Request $request, CoreProjectModal $project)
    {
        $query = TimeRegistrationModal::where('project_id', $project->id);

        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('date', [
                Carbon::parse($request->start_date),
                Carbon::parse($request->end_date)
            ]);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $teamMembersCount = User::whereHas('tasks', function ($q) use ($project) {
            $q->where('project_id', $project->id)
              ->whereNull('deleted_at');
        })->orWhere('id', $project->manager_id)
          ->distinct()
          ->count();

        return response()->json([
            'total_hours' => $query->sum('hours'),
            'billable_hours' => (clone $query)->where('billable', true)->sum('hours'),
            'overtime_hours' => (clone $query)->where('overtime', true)->sum('hours'),
            'team_members_count' => $teamMembersCount
        ]);
    }
}
