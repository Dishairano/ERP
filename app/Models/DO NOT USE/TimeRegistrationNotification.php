<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeRegistrationNotification extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'user_id',
    'type',
    'message',
    'is_read',
    'read_at'
  ];

  protected $casts = [
    'is_read' => 'boolean',
    'read_at' => 'datetime'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
