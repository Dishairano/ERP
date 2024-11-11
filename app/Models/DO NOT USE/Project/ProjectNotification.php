<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class ProjectNotification extends Model
{
  use HasFactory;

  protected $table = 'project_notifications';

  protected $fillable = [
    'project_id',
    'user_id',
    'type',
    'title',
    'message',
    'read_at',
    'data'
  ];

  protected $casts = [
    'read_at' => 'datetime',
    'data' => 'array'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
