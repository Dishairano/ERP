<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'date',
    'hours',
    'reason',
    'status'
  ];

  protected $casts = [
    'date' => 'date',
    'hours' => 'float'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
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
