<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectPhase extends Model
{
  use HasFactory;

  protected $table = 'project_phases';

  protected $fillable = [
    'name',
    'description',
    'project_id',
    'start_date',
    'end_date',
    'status'
  ];

  protected $casts = [
    'start_date' => 'datetime',
    'end_date' => 'datetime'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function tasks()
  {
    return $this->hasMany(ProjectTask::class, 'phase_id');
  }

  public function documents()
  {
    return $this->hasMany(ProjectDocument::class, 'phase_id');
  }
}
