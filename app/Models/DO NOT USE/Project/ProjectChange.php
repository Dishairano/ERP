<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class ProjectChange extends Model
{
  use HasFactory;

  protected $table = 'project_changes';

  protected $fillable = [
    'project_id',
    'user_id',
    'change_type',
    'description',
    'old_value',
    'new_value',
    'status',
    'impact_assessment',
    'approved_by',
    'approved_at'
  ];

  protected $casts = [
    'approved_at' => 'datetime'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function approver()
  {
    return $this->belongsTo(User::class, 'approved_by');
  }
}
