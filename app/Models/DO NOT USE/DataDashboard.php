<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataDashboard extends Model
{
  protected $fillable = [
    'name',
    'user_id',
    'layout',
    'is_public'
  ];

  protected $casts = [
    'layout' => 'array',
    'is_public' => 'boolean'
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function components(): HasMany
  {
    return $this->hasMany(DashboardComponent::class, 'dashboard_id');
  }
}
