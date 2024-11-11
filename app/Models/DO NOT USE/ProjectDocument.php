<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectDocument extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'project_id',
    'phase_id',
    'task_id',
    'name',
    'file_path',
    'file_type',
    'file_size',
    'version',
    'uploaded_by'
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

  public function getFileSizeForHumans()
  {
    $bytes = $this->file_size;
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $index = 0;
    while ($bytes >= 1024 && $index < count($units) - 1) {
      $bytes /= 1024;
      $index++;
    }
    return round($bytes, 2) . ' ' . $units[$index];
  }
}
