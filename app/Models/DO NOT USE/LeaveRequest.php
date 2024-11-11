<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'leave_type_id',
    'start_date',
    'end_date',
    'reason',
    'status',
    'comments'
  ];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function leaveType()
  {
    return $this->belongsTo(LeaveType::class);
  }

  public function getDurationAttribute()
  {
    return $this->start_date->diffInDays($this->end_date) + 1;
  }

  public function scopePending($query)
  {
    return $query->where('status', 'pending');
  }

  public function scopeApproved($query)
  {
    return $query->where('status', 'approved');
  }

  public function scopeRejected($query)
  {
    return $query->where('status', 'rejected');
  }
}
