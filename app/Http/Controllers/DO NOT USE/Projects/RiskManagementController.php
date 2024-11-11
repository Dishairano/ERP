<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectRisk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RiskManagementController extends Controller
{
  public function index(Request $request)
  {
    $query = ProjectRisk::with(['project', 'assignedTo', 'mitigationActions'])
      ->when($request->project_id, function ($q) use ($request) {
        return $q->where('project_id', $request->project_id);
      })
      ->when($request->priority, function ($q) use ($request) {
        return $q->where('priority', $request->priority);
      })
      ->when($request->status, function ($q) use ($request) {
        return $q->where('status', $request->status);
      });

    $risks = $query->orderBy('priority', 'desc')
      ->orderBy('probability', 'desc')
      ->paginate(15);

    $riskMetrics = $this->getRiskMetrics();
    $projects = Project::select('id', 'name')->get();

    return view('projects.risks.index', compact('risks', 'riskMetrics', 'projects'));
  }

  protected function getRiskMetrics()
  {
    return [
      'total' => ProjectRisk::count(),
      'high_priority' => ProjectRisk::where('priority', 'high')->count(),
      'medium_priority' => ProjectRisk::where('priority', 'medium')->count(),
      'low_priority' => ProjectRisk::where('priority', 'low')->count(),
      'mitigated' => ProjectRisk::where('status', 'mitigated')->count(),
      'risk_exposure' => $this->calculateRiskExposure(),
      'mitigation_rate' => $this->calculateMitigationRate(),
      'risk_trend' => $this->calculateRiskTrend()
    ];
  }

  protected function calculateRiskExposure()
  {
    $risks = ProjectRisk::where('status', '!=', 'mitigated')
      ->select('priority', 'probability', 'impact')
      ->get();

    $totalExposure = 0;
    foreach ($risks as $risk) {
      $priorityWeight = $this->getPriorityWeight($risk->priority);
      $probabilityScore = $risk->probability;
      $impactScore = $risk->impact;

      $totalExposure += ($priorityWeight * $probabilityScore * $impactScore);
    }

    return $risks->count() > 0 ? round($totalExposure / $risks->count(), 2) : 0;
  }

  protected function getPriorityWeight($priority)
  {
    return [
      'low' => 1,
      'medium' => 2,
      'high' => 3
    ][$priority] ?? 1;
  }

  protected function calculateMitigationRate()
  {
    $totalRisks = ProjectRisk::count();
    if ($totalRisks === 0) return 0;

    $mitigatedRisks = ProjectRisk::where('status', 'mitigated')->count();
    return round(($mitigatedRisks / $totalRisks) * 100, 2);
  }

  protected function calculateRiskTrend()
  {
    $currentMonth = now()->startOfMonth();
    $lastMonth = now()->subMonth()->startOfMonth();

    $currentMonthRisks = ProjectRisk::where('created_at', '>=', $currentMonth)->count();
    $lastMonthRisks = ProjectRisk::whereBetween('created_at', [$lastMonth, $currentMonth])->count();

    if ($lastMonthRisks === 0) return 0;

    $trend = (($currentMonthRisks - $lastMonthRisks) / $lastMonthRisks) * 100;
    return round($trend, 2);
  }

  public function create()
  {
    $projects = Project::select('id', 'name')->get();
    return view('projects.risks.create', compact('projects'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'project_id' => 'required|exists:projects,id',
      'priority' => 'required|in:low,medium,high',
      'probability' => 'required|integer|between:1,10',
      'impact' => 'required|integer|between:1,10',
      'status' => 'required|in:identified,analyzing,mitigating,mitigated,accepted',
      'mitigation_strategy' => 'required|string',
      'contingency_plan' => 'required|string',
      'trigger_events' => 'required|array',
      'trigger_events.*' => 'string',
      'assigned_to' => 'required|exists:users,id'
    ]);

    $risk = ProjectRisk::create([
      ...$validated,
      'created_by' => Auth::id(),
      'risk_score' => $validated['probability'] * $validated['impact']
    ]);

    return redirect()->route('projects.risks.index')
      ->with('success', 'Risk created successfully');
  }

  public function show(ProjectRisk $risk)
  {
    $risk->load(['project', 'assignedTo', 'mitigationActions', 'creator']);
    return view('projects.risks.show', compact('risk'));
  }

  public function edit(ProjectRisk $risk)
  {
    $risk->load(['project']);
    $projects = Project::select('id', 'name')->get();
    return view('projects.risks.edit', compact('risk', 'projects'));
  }

  public function update(Request $request, ProjectRisk $risk)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'project_id' => 'required|exists:projects,id',
      'priority' => 'required|in:low,medium,high',
      'probability' => 'required|integer|between:1,10',
      'impact' => 'required|integer|between:1,10',
      'status' => 'required|in:identified,analyzing,mitigating,mitigated,accepted',
      'mitigation_strategy' => 'required|string',
      'contingency_plan' => 'required|string',
      'trigger_events' => 'required|array',
      'trigger_events.*' => 'string',
      'assigned_to' => 'required|exists:users,id'
    ]);

    $risk->update([
      ...$validated,
      'risk_score' => $validated['probability'] * $validated['impact']
    ]);

    return redirect()->route('projects.risks.index')
      ->with('success', 'Risk updated successfully');
  }

  public function destroy(ProjectRisk $risk)
  {
    $risk->delete();
    return redirect()->route('projects.risks.index')
      ->with('success', 'Risk deleted successfully');
  }

  public function matrix()
  {
    $risks = ProjectRisk::with('project')
      ->where('status', '!=', 'mitigated')
      ->get()
      ->groupBy('priority');

    $matrix = [
      'high' => [
        'count' => $risks->get('high', collect())->count(),
        'risks' => $risks->get('high', collect())
      ],
      'medium' => [
        'count' => $risks->get('medium', collect())->count(),
        'risks' => $risks->get('medium', collect())
      ],
      'low' => [
        'count' => $risks->get('low', collect())->count(),
        'risks' => $risks->get('low', collect())
      ]
    ];

    return view('projects.risks.matrix', compact('matrix'));
  }

  public function report()
  {
    $monthlyTrends = $this->getMonthlyRiskTrends();
    $projectRiskDistribution = $this->getProjectRiskDistribution();
    $mitigationEffectiveness = $this->getMitigationEffectiveness();

    return view('projects.risks.report', compact(
      'monthlyTrends',
      'projectRiskDistribution',
      'mitigationEffectiveness'
    ));
  }

  protected function getMonthlyRiskTrends()
  {
    return ProjectRisk::select(
      DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
      DB::raw('COUNT(*) as total'),
      DB::raw('SUM(CASE WHEN priority = "high" THEN 1 ELSE 0 END) as high_risks'),
      DB::raw('SUM(CASE WHEN priority = "medium" THEN 1 ELSE 0 END) as medium_risks'),
      DB::raw('SUM(CASE WHEN priority = "low" THEN 1 ELSE 0 END) as low_risks')
    )
      ->where('created_at', '>=', now()->subMonths(12))
      ->groupBy('month')
      ->orderBy('month')
      ->get();
  }

  protected function getProjectRiskDistribution()
  {
    return Project::withCount(['risks as total_risks'])
      ->withCount(['risks as high_risks' => function ($query) {
        $query->where('priority', 'high');
      }])
      ->withCount(['risks as medium_risks' => function ($query) {
        $query->where('priority', 'medium');
      }])
      ->withCount(['risks as low_risks' => function ($query) {
        $query->where('priority', 'low');
      }])
      ->having('total_risks', '>', 0)
      ->orderByDesc('total_risks')
      ->limit(10)
      ->get();
  }

  protected function getMitigationEffectiveness()
  {
    return ProjectRisk::where('status', 'mitigated')
      ->select(
        'mitigation_strategy',
        DB::raw('COUNT(*) as total_mitigated'),
        DB::raw('AVG(TIMESTAMPDIFF(DAY, created_at, updated_at)) as avg_days_to_mitigate')
      )
      ->groupBy('mitigation_strategy')
      ->having('total_mitigated', '>', 0)
      ->orderByDesc('total_mitigated')
      ->get();
  }
}
