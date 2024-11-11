<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityNotification extends Model
{
  protected $fillable = [
    'user_id',
    'type',
    'title',
    'message',
    'details',
    'read_at'
  ];

  protected $casts = [
    'details' => 'json',
    'read_at' => 'datetime'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
