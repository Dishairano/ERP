<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoreDashboardAnalyticsModal extends Model
{
  protected $table = 'dashboards';

  protected $fillable = [
    'title',
    'description',
    'type',
    'data_source',
    'refresh_interval',
    'layout_config',
    'is_active',
    'created_by',
    'updated_by'
  ];

  protected $casts = [
    'layout_config' => 'json',
    'is_active' => 'boolean',
    'refresh_interval' => 'integer'
  ];

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function updater()
  {
    return $this->belongsTo(User::class, 'updated_by');
  }

  public function components()
  {
    return $this->hasMany(DashboardComponent::class, 'dashboard_id');
  }

  public function category()
  {
    return $this->belongsTo(DashboardCategory::class);
  }

  public function preferences()
  {
    return $this->hasMany(DashboardPreference::class);
  }
}
