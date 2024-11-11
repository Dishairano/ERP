<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplianceNotification extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'title',
    'message',
    'type',
    'priority',
    'status',
    'user_id',
    'read_at',
    'action_required',
    'due_date'
  ];

  protected $casts = [
    'read_at' => 'datetime',
    'due_date' => 'date'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function requirement()
  {
    return $this->belongsTo(ComplianceRequirement::class);
  }
}
