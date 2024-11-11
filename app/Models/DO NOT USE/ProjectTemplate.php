<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTemplate extends Model
{
  use HasFactory, SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'description',
    'default_phases',
    'default_tasks',
    'default_risks',
    'default_team_structure',
    'default_budget_allocation',
    'is_active',
    'created_by',
    'updated_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'default_phases' => 'array',
    'default_tasks' => 'array',
    'default_risks' => 'array',
    'default_team_structure' => 'array',
    'default_budget_allocation' => 'array',
    'is_active' => 'boolean'
  ];

  /**
   * Get the user who created the template.
   */
  public function createdBy()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the user who last updated the template.
   */
  public function updatedBy()
  {
    return $this->belongsTo(User::class, 'updated_by');
  }

  /**
   * Scope a query to only include active templates.
   */
  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  /**
   * Get the projects that use this template.
   */
  public function projects()
  {
    return $this->hasMany(Project::class, 'template_id');
  }
}
