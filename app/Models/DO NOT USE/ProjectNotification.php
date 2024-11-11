<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectNotification extends Model
{
  use HasFactory;

  protected $fillable = [
    'project_id',
    'type',
    'title',
    'content',
    'metadata',
    'read_at'
  ];

  protected $casts = [
    'metadata' => 'array',
    'read_at' => 'datetime'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function markAsRead()
  {
    $this->read_at = now();
    $this->save();
  }

  public function isRead()
  {
    return !is_null($this->read_at);
  }
}
