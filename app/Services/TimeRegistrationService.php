<?php

namespace App\Services;

use App\Models\TimeRegistration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TimeRegistrationService
{
  /**
   * Get time registrations summary for a given period
   */
  public function getSummary(User $user, ?string $startDate = null, ?string $endDate = null): array
  {
    $query = TimeRegistration::where('user_id', $user->id);

    if ($startDate && $endDate) {
      $query->whereBetween('start_time', [
        Carbon::parse($startDate)->startOfDay(),
        Carbon::parse($endDate)->endOfDay()
      ]);
    }

    $totalMinutes = $query->sum('duration_minutes');
    $billableMinutes = $query->where('is_billable', true)->sum('duration_minutes');
    $totalAmount = $query->where('is_billable', true)
      ->get()
      ->sum(function ($registration) {
        return $registration->billable_amount;
      });

    return [
      'total_hours' => round($totalMinutes / 60, 2),
      'billable_hours' => round($billableMinutes / 60, 2),
      'total_amount' => $totalAmount,
      'utilization_rate' => $totalMinutes > 0
        ? round(($billableMinutes / $totalMinutes) * 100, 2)
        : 0
    ];
  }

  /**
   * Get time registrations grouped by project
   */
  public function getProjectSummary(User $user, ?string $startDate = null, ?string $endDate = null): Collection
  {
    $query = TimeRegistration::where('user_id', $user->id)
      ->with('project');

    if ($startDate && $endDate) {
      $query->whereBetween('start_time', [
        Carbon::parse($startDate)->startOfDay(),
        Carbon::parse($endDate)->endOfDay()
      ]);
    }

    return $query->get()
      ->groupBy('project_id')
      ->map(function ($registrations) {
        $totalMinutes = $registrations->sum('duration_minutes');
        $billableMinutes = $registrations->where('is_billable', true)->sum('duration_minutes');
        $totalAmount = $registrations->where('is_billable', true)
          ->sum(function ($registration) {
            return $registration->billable_amount;
          });

        return [
          'project' => $registrations->first()->project,
          'total_hours' => round($totalMinutes / 60, 2),
          'billable_hours' => round($billableMinutes / 60, 2),
          'total_amount' => $totalAmount,
          'utilization_rate' => $totalMinutes > 0
            ? round(($billableMinutes / $totalMinutes) * 100, 2)
            : 0
        ];
      });
  }

  /**
   * Get time registrations grouped by category
   */
  public function getCategorySummary(User $user, ?string $startDate = null, ?string $endDate = null): Collection
  {
    $query = TimeRegistration::where('user_id', $user->id)
      ->with('category');

    if ($startDate && $endDate) {
      $query->whereBetween('start_time', [
        Carbon::parse($startDate)->startOfDay(),
        Carbon::parse($endDate)->endOfDay()
      ]);
    }

    return $query->get()
      ->groupBy('time_category_id')
      ->map(function ($registrations) {
        $totalMinutes = $registrations->sum('duration_minutes');
        return [
          'category' => $registrations->first()->category,
          'total_hours' => round($totalMinutes / 60, 2),
          'percentage' => round(($totalMinutes / $registrations->sum('duration_minutes')) * 100, 2)
        ];
      });
  }

  /**
   * Check for overlapping time registrations
   */
  public function hasOverlap(TimeRegistration $timeRegistration): bool
  {
    return TimeRegistration::where('user_id', $timeRegistration->user_id)
      ->where('id', '!=', $timeRegistration->id)
      ->where(function ($query) use ($timeRegistration) {
        $query->whereBetween('start_time', [
          $timeRegistration->start_time,
          $timeRegistration->end_time
        ])
          ->orWhereBetween('end_time', [
            $timeRegistration->start_time,
            $timeRegistration->end_time
          ]);
      })
      ->exists();
  }

  /**
   * Get weekly time registration summary
   */
  public function getWeeklySummary(User $user, Carbon $startOfWeek = null): array
  {
    $startOfWeek = $startOfWeek ?? Carbon::now()->startOfWeek();
    $endOfWeek = $startOfWeek->copy()->endOfWeek();

    $registrations = TimeRegistration::where('user_id', $user->id)
      ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
      ->get();

    $dailySummary = [];
    $currentDay = $startOfWeek->copy();

    while ($currentDay <= $endOfWeek) {
      $dayRegistrations = $registrations->filter(function ($registration) use ($currentDay) {
        return $registration->start_time->isSameDay($currentDay);
      });

      $totalMinutes = $dayRegistrations->sum('duration_minutes');
      $billableMinutes = $dayRegistrations->where('is_billable', true)->sum('duration_minutes');

      $dailySummary[$currentDay->format('Y-m-d')] = [
        'date' => $currentDay->format('Y-m-d'),
        'day_name' => $currentDay->format('l'),
        'total_hours' => round($totalMinutes / 60, 2),
        'billable_hours' => round($billableMinutes / 60, 2),
        'entries' => $dayRegistrations->count()
      ];

      $currentDay->addDay();
    }

    return [
      'daily_summary' => $dailySummary,
      'week_total' => [
        'total_hours' => round($registrations->sum('duration_minutes') / 60, 2),
        'billable_hours' => round($registrations->where('is_billable', true)->sum('duration_minutes') / 60, 2),
        'total_entries' => $registrations->count()
      ]
    ];
  }

  /**
   * Get recommended time categories based on project and task
   */
  public function getRecommendedCategories(int $projectId = null, int $taskId = null): Collection
  {
    $query = TimeRegistration::with('category')
      ->where(function ($q) use ($projectId, $taskId) {
        if ($projectId) {
          $q->where('project_id', $projectId);
        }
        if ($taskId) {
          $q->where('project_task_id', $taskId);
        }
      })
      ->whereNotNull('time_category_id')
      ->select('time_category_id')
      ->selectRaw('COUNT(*) as usage_count')
      ->groupBy('time_category_id')
      ->orderByDesc('usage_count')
      ->limit(5);

    return $query->get()->map(function ($result) {
      return [
        'category' => $result->category,
        'usage_count' => $result->usage_count
      ];
    });
  }
}
