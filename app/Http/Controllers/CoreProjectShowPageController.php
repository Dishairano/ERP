<?php

namespace App\Http\Controllers;

use App\Models\CoreProjectModal;
use App\Models\User;
use Illuminate\Http\Request;

class CoreProjectShowPageController extends Controller
{
  public function show(CoreProjectModal $project)
  {
    // Load relationships
    $project->load([
      'tasks' => function ($query) {
        $query->latest()->take(5);
      },
      'tasks.assignedTo',
      'risks' => function ($query) {
        $query->latest()->take(5);
      },
      'manager'
    ]);

    // Get users for task assignment
    $users = User::where('deleted_at', null)->get();

    // Get task statistics
    $taskStats = [
      'total' => $project->tasks()->count(),
      'completed' => $project->tasks()->where('status', 'completed')->count(),
      'in_progress' => $project->tasks()->where('status', 'in_progress')->count(),
      'pending' => $project->tasks()->where('status', 'pending')->count(),
      'high_priority' => $project->tasks()->where('priority', 'high')->count()
    ];

    // Get risk statistics
    $riskStats = [
      'total' => $project->risks()->count(),
      'critical' => $project->risks()->whereRaw('severity * likelihood >= ?', [16])->count(),
      'high' => $project->risks()->whereRaw('severity * likelihood >= ? AND severity * likelihood < ?', [9, 16])->count(),
      'medium' => $project->risks()->whereRaw('severity * likelihood >= ? AND severity * likelihood < ?', [4, 9])->count(),
      'low' => $project->risks()->whereRaw('severity * likelihood < ?', [4])->count(),
      'mitigated' => $project->risks()->where('status', 'mitigated')->count()
    ];

    return view('content.projects.show', compact('project', 'users', 'taskStats', 'riskStats'));
  }
}
