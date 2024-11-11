<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataIntegration extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'name',
    'source_type',
    'connection_type',
    'connection_details',
    'is_active',
    'sync_interval',
    'last_sync'
  ];

  protected $casts = [
    'connection_details' => 'json',
    'is_active' => 'boolean',
    'last_sync' => 'datetime',
  ];

  public function mappings()
  {
    return $this->hasMany(DataMapping::class);
  }

  public function syncLogs()
  {
    return $this->hasMany(SyncLog::class);
  }

  public function apiConfiguration()
  {
    return $this->hasOne(ApiConfiguration::class);
  }

  public function databaseConnection()
  {
    return $this->hasOne(DatabaseConnection::class);
  }

  public function schedule()
  {
    return $this->hasOne(IntegrationSchedule::class);
  }

  public function validationRules()
  {
    return $this->hasMany(DataValidationRule::class);
  }
}
