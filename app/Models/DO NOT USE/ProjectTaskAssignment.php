<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTaskAssignment extends Model
{
  use HasFactory;

  protected $fillable = [
    'task_id',
    'user_id',
    'role'
  ];

  // Constants for RACI roles
  const ROLE_RESPONSIBLE = 'responsible';
  const ROLE_ACCOUNTABLE = 'accountable';
  const ROLE_CONSULTED = 'consulted';
  const ROLE_INFORMED = 'informed';

  // Relationships
  public function task()
  {
    return $this->belongsTo(ProjectTask::class, 'task_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  // Scopes
  public function scopeResponsible($query)
  {
    return $query->where('role', self::ROLE_RESPONSIBLE);
  }

  public function scopeAccountable($query)
  {
    return $query->where('role', self::ROLE_ACCOUNTABLE);
  }

  public function scopeConsulted($query)
  {
    return $query->where('role', self::ROLE_CONSULTED);
  }

  public function scopeInformed($query)
  {
    return $query->where('role', self::ROLE_INFORMED);
  }

  // Helper methods
  public function isResponsible()
  {
    return $this->role === self::ROLE_RESPONSIBLE;
  }

  public function isAccountable()
  {
    return $this->role === self::ROLE_ACCOUNTABLE;
  }

  public function isConsulted()
  {
    return $this->role === self::ROLE_CONSULTED;
  }

  public function isInformed()
  {
    return $this->role === self::ROLE_INFORMED;
  }

  public function getProjectId()
  {
    return $this->task->project_id;
  }

  // Validation rules
  public static function getRoles()
  {
    return [
      self::ROLE_RESPONSIBLE,
      self::ROLE_ACCOUNTABLE,
      self::ROLE_CONSULTED,
      self::ROLE_INFORMED
    ];
  }

  protected static function boot()
  {
    parent::boot();

    static::saving(function ($assignment) {
      // Ensure only one person is accountable per task
      if ($assignment->role === self::ROLE_ACCOUNTABLE) {
        $existingAccountable = static::where('task_id', $assignment->task_id)
          ->where('role', self::ROLE_ACCOUNTABLE)
          ->where('id', '!=', $assignment->id)
          ->exists();

        if ($existingAccountable) {
          throw new \Exception('Only one person can be accountable for a task.');
        }
      }
    });
  }
}
