<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'leave_type_id',
    'year',
    'total_days',
    'used_days',
    'pending_days'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function leaveType()
  {
    return $this->belongsTo(LeaveType::class);
  }

  public function getRemainingDaysAttribute()
  {
    return $this->total_days - $this->used_days - $this->pending_days;
  }
}
