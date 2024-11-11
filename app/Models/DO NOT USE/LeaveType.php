<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'description',
    'days_per_year',
    'requires_approval',
    'paid'
  ];

  protected $casts = [
    'requires_approval' => 'boolean',
    'paid' => 'boolean'
  ];

  public function leaveRequests()
  {
    return $this->hasMany(LeaveRequest::class);
  }

  public function leaveBalances()
  {
    return $this->hasMany(LeaveBalance::class);
  }
}
