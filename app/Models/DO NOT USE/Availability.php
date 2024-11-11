<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'date',
    'start_time',
    'end_time',
    'status',
    'reason'
  ];

  protected $casts = [
    'date' => 'date',
    'start_time' => 'datetime',
    'end_time' => 'datetime'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function scopeAvailable($query)
  {
    return $query->where('status', 'available');
  }

  public function scopeUnavailable($query)
  {
    return $query->where('status', 'unavailable');
  }
}
