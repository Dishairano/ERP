<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DashboardComponent extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'type',
    'settings',
    'position',
    'size',
    'refresh_interval',
    'is_enabled',
    'user_id',
    'dashboard_id',
    'data_source',
    'visualization_type',
    'custom_styles',
    'permissions'
  ];

  protected $casts = [
    'settings' => 'json',
    'custom_styles' => 'json',
    'permissions' => 'json',
    'is_enabled' => 'boolean',
  ];

  // Relationships
  public function dashboard()
  {
    return $this->belongsTo(Dashboard::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function dataSource()
  {
    return $this->belongsTo(DataSource::class);
  }

  // Scopes
  public function scopeEnabled($query)
  {
    return $query->where('is_enabled', true);
  }

  public function scopeByType($query, $type)
  {
    return $query->where('type', $type);
  }

  // Methods
  public function refreshData()
  {
    // Logic to refresh component data
    $data = $this->fetchDataFromSource();
    $this->update(['last_refresh' => now()]);
    return $data;
  }

  public function updatePosition($position)
  {
    return $this->update(['position' => $position]);
  }

  public function updateSize($size)
  {
    return $this->update(['size' => $size]);
  }

  protected function fetchDataFromSource()
  {
    // Implementation for fetching data from the specified data source
    switch ($this->data_source) {
      case 'database':
        return $this->fetchFromDatabase();
      case 'api':
        return $this->fetchFromApi();
      case 'cache':
        return $this->fetchFromCache();
      default:
        return null;
    }
  }

  protected function fetchFromDatabase()
  {
    // Implement database data fetching logic
  }

  protected function fetchFromApi()
  {
    // Implement API data fetching logic
  }

  protected function fetchFromCache()
  {
    // Implement cache data fetching logic
  }
}
