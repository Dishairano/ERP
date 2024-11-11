<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class ProjectFeedback extends Model
{
  use HasFactory;

  protected $table = 'project_feedback';

  protected $fillable = [
    'project_id',
    'user_id',
    'feedback_type',
    'content',
    'rating',
    'status',
    'response',
    'responded_by',
    'responded_at'
  ];

  protected $casts = [
    'rating' => 'integer',
    'responded_at' => 'datetime'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function responder()
  {
    return $this->belongsTo(User::class, 'responded_by');
  }
}
