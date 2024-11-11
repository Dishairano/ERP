<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseUser extends Authenticatable
{
  use HasFactory, Notifiable, SoftDeletes;

  protected $fillable = [
    'name',
    'email',
    'password',
    'avatar',
    'hourly_rate',
    'last_login_at'
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
    'last_login_at' => 'datetime',
    'hourly_rate' => 'decimal:2'
  ];
}
