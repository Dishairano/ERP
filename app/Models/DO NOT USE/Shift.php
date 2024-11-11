<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'start_time',
    'end_time',
    'description'
  ];

  protected $casts = [
    'start_time' => 'datetime',
    'end_time' => 'datetime'
  ];

  public function rosters()
  {
    return $this->hasMany(Roster::class);
  }

  public function getDurationAttribute()
  {
    return $this->start_time->diffInHours($this->end_time);
  }
}
