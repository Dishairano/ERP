<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectChange extends Model
{
  use HasFactory;

  protected $fillable = [
    'project_id',
    'user_id',
    'change_type',
    'description',
    'old_value',
    'new_value',
    'status',
    'reason',
    'approved_by',
    'approved_at'
  ];

  protected $casts = [
    'old_value' => 'array',
    'new_value' => 'array',
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

  public function approve(User $approver)
  {
    $this->approved_by = $approver->id;
    $this->approved_at = now();
    $this->status = 'approved';
    $this->save();
  }

  public function reject($reason)
  {
    $this->status = 'rejected';
    $this->reason = $reason;
    $this->save();
  }
}
