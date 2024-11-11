<?php

namespace App\Models;

use App\Contracts\Authorizable;
use App\Traits\HasUserFeatures;
use App\Traits\HasAuthorization;
use App\Traits\HasLaravelAuthorization;
use App\Traits\HasDashboardPreferences;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements Authorizable
{
  use Notifiable,
    SoftDeletes,
    HasUserFeatures,
    HasAuthorization,
    HasLaravelAuthorization,
    HasDashboardPreferences;

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
