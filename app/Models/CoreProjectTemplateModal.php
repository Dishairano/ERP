<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreProjectTemplateModal extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'project_templates';

  protected $fillable = [
    'name',
    'description',
    'category',
    'created_by',
    'template_data',
    'estimated_duration',
    'default_settings',
    'is_active',
    'usage_count'
  ];

  protected $casts = [
    'template_data' => 'array',
    'default_settings' => 'array',
    'is_active' => 'boolean',
    'usage_count' => 'integer'
  ];

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function projects()
  {
    return $this->hasMany(CoreProjectModal::class, 'template_id');
  }

  public function getTaskTemplatesAttribute()
  {
    return $this->template_data['tasks'] ?? [];
  }

  public function getRiskTemplatesAttribute()
  {
    return $this->template_data['risks'] ?? [];
  }

  public function getMilestoneTemplatesAttribute()
  {
    return $this->template_data['milestones'] ?? [];
  }

  public function getTeamRolesAttribute()
  {
    return $this->template_data['team_roles'] ?? [];
  }

  public function getEstimatedBudgetAttribute()
  {
    return $this->template_data['estimated_budget'] ?? 0;
  }

  public function getRequiredSkillsAttribute()
  {
    return $this->template_data['required_skills'] ?? [];
  }

  public function getSuccessfulProjectsCountAttribute()
  {
    return $this->projects()
      ->where('status', 'completed')
      ->where('progress_percentage', '>=', 90)
      ->count();
  }

  public function getAverageCompletionTimeAttribute()
  {
    $completedProjects = $this->projects()
      ->where('status', 'completed')
      ->get();

    if ($completedProjects->isEmpty()) {
      return null;
    }

    $totalDays = $completedProjects->sum(function ($project) {
      return $project->start_date->diffInDays($project->end_date);
    });

    return round($totalDays / $completedProjects->count());
  }

  public function getAverageBudgetVarianceAttribute()
  {
    $completedProjects = $this->projects()
      ->where('status', 'completed')
      ->get();

    if ($completedProjects->isEmpty()) {
      return null;
    }

    $totalVariance = $completedProjects->sum(function ($project) {
      return (($project->budget_spent - $project->budget_allocated) / $project->budget_allocated) * 100;
    });

    return round($totalVariance / $completedProjects->count(), 2);
  }

  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  public function scopeByCategory($query, $category)
  {
    return $query->where('category', $category);
  }

  public function scopePopular($query)
  {
    return $query->orderBy('usage_count', 'desc');
  }

  public function scopeSuccessful($query, $minSuccessRate = 80)
  {
    return $query->whereHas('projects', function ($q) use ($minSuccessRate) {
      $q->where('status', 'completed')
        ->where('progress_percentage', '>=', $minSuccessRate);
    });
  }

  protected static function booted()
  {
    static::creating(function ($template) {
      if (!isset($template->template_data['created_at'])) {
        $template->template_data = array_merge(
          $template->template_data ?? [],
          ['created_at' => now()->toDateTimeString()]
        );
      }
    });

    static::updating(function ($template) {
      if (!isset($template->template_data['updated_at'])) {
        $template->template_data = array_merge(
          $template->template_data ?? [],
          ['updated_at' => now()->toDateTimeString()]
        );
      }
    });
  }

  public function incrementUsageCount()
  {
    $this->increment('usage_count');
  }

  public function duplicate()
  {
    $clone = $this->replicate(['usage_count']);
    $clone->name = $this->name . ' (Copy)';
    $clone->usage_count = 0;
    $clone->save();

    return $clone;
  }
}
