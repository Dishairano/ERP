<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectFeedback extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'project_id',
    'phase_id',
    'task_id',
    'user_id',
    'content',
    'type',
    'rating'
  ];

  protected $casts = [
    'rating' => 'integer'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function phase()
  {
    return $this->belongsTo(ProjectPhase::class);
  }

  public function task()
  {
    return $this->belongsTo(ProjectTask::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
