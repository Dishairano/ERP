<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class ProjectDocument extends Model
{
  use HasFactory;

  protected $table = 'project_documents';

  protected $fillable = [
    'name',
    'file_path',
    'file_type',
    'file_size',
    'version',
    'project_id',
    'phase_id',
    'task_id',
    'uploaded_by'
  ];

  protected $casts = [
    'file_size' => 'integer'
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

  public function uploader()
  {
    return $this->belongsTo(User::class, 'uploaded_by');
  }
}
