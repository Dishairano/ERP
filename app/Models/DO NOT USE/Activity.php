<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'user_id',
    'project_id',
    'type',
    'action',
    'description',
    'metadata',
    'changes',
    'subject_type',
    'subject_id'
  ];

  protected $casts = [
    'metadata' => 'array',
    'changes' => 'array'
  ];

  protected $primaryKey = 'id';

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function subject()
  {
    return $this->morphTo();
  }

  public static function log($action, $description, $userId = null, $projectId = null, $metadata = null, $subject = null, $changes = null)
  {
    return static::create([
      'user_id' => $userId ?? auth()->id(),
      'project_id' => $projectId,
      'action' => $action,
      'description' => $description,
      'metadata' => $metadata,
      'subject_type' => $subject ? get_class($subject) : null,
      'subject_id' => $subject ? $subject->getKey() : null,
      'changes' => $changes,
      'type' => static::determineType($action)
    ]);
  }

  protected static function determineType($action)
  {
    return match (true) {
      str_contains($action, 'create') => 'create',
      str_contains($action, 'update') => 'update',
      str_contains($action, 'delete') => 'delete',
      str_contains($action, 'complete') => 'complete',
      str_contains($action, 'assign') => 'assign',
      str_contains($action, 'comment') => 'comment',
      default => 'other'
    };
  }

  public function getIconClass()
  {
    return match ($this->type) {
      'create' => 'ri-add-circle-line text-success',
      'update' => 'ri-edit-line text-primary',
      'delete' => 'ri-delete-bin-line text-danger',
      'complete' => 'ri-check-double-line text-success',
      'assign' => 'ri-user-add-line text-info',
      'comment' => 'ri-chat-1-line text-warning',
      default => 'ri-information-line text-secondary'
    };
  }

  public function scopeRecent($query)
  {
    return $query->orderBy('created_at', 'desc');
  }

  public function scopeByType($query, $type)
  {
    return $query->where('type', $type);
  }

  public function scopeForProject($query, $projectId)
  {
    return $query->where('project_id', $projectId);
  }

  public function scopeByUser($query, $userId)
  {
    return $query->where('user_id', $userId);
  }

  public function scopeWithinDays($query, $days)
  {
    return $query->where('created_at', '>=', now()->subDays($days));
  }
}
