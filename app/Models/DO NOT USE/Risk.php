<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
  protected $fillable = [
    'project_id',
    'title',
    'description',
    'severity',
    'likelihood',
    'impact',
    'mitigation_strategy',
    'status',
    'identified_by',
    'assigned_to',
    'due_date'
  ];

  protected $casts = [
    'due_date' => 'date',
    'identified_at' => 'datetime'
  ];

  public function project()
  {
    return $this->belongsTo(CoreProjectDashboardModal::class, 'project_id');
  }

  public function identifier()
  {
    return $this->belongsTo(User::class, 'identified_by');
  }

  public function assignee()
  {
    return $this->belongsTo(User::class, 'assigned_to');
  }

  public function getRiskScoreAttribute()
  {
    return $this->likelihood * $this->impact;
  }
}
