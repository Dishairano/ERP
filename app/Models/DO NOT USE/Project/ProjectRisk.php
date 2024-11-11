<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class ProjectRisk extends Model
{
  use HasFactory;

  protected $table = 'project_risks';

  protected $fillable = [
    'name',
    'description',
    'project_id',
    'status',
    'probability',
    'impact',
    'mitigation_strategy',
    'owner_id',
    'identification_date'
  ];

  protected $casts = [
    'probability' => 'integer',
    'impact' => 'integer',
    'identification_date' => 'datetime'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function owner()
  {
    return $this->belongsTo(User::class, 'owner_id');
  }
}
