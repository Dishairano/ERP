<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class DataSourceLog extends Model
{
  use HasFactory;

  protected $fillable = [
    'data_source_id',
    'type', // success, error, warning, info
    'message',
    'details',
    'stack_trace',
    'execution_time',
    'memory_usage',
    'user_id',
    'ip_address',
    'request_data',
    'response_data'
  ];

  protected $casts = [
    'details' => 'json',
    'request_data' => 'json',
    'response_data' => 'json',
    'execution_time' => 'float',
    'memory_usage' => 'integer'
  ];

  // Relationships
  public function dataSource()
  {
    return $this->belongsTo(DataSource::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  // Scopes
  public function scopeErrors($query)
  {
    return $query->where('type', 'error');
  }

  public function scopeSuccesses($query)
  {
    return $query->where('type', 'success');
  }

  public function scopeWarnings($query)
  {
    return $query->where('type', 'warning');
  }

  public function scopeRecent($query, $hours = 24)
  {
    return $query->where('created_at', '>=', now()->subHours($hours));
  }

  // Methods
  public static function logError($dataSourceId, $message, $details = [])
  {
    return self::create([
      'data_source_id' => $dataSourceId,
      'type' => 'error',
      'message' => $message,
      'details' => $details,
      'user_id' => Auth::check() ? Auth::user()->getAuthIdentifier() : null,
      'ip_address' => request()->ip(),
      'execution_time' => defined('LARAVEL_START') ? microtime(true) - LARAVEL_START : 0,
      'memory_usage' => memory_get_usage(true)
    ]);
  }

  public static function logSuccess($dataSourceId, $message, $details = [])
  {
    return self::create([
      'data_source_id' => $dataSourceId,
      'type' => 'success',
      'message' => $message,
      'details' => $details,
      'user_id' => Auth::check() ? Auth::user()->getAuthIdentifier() : null,
      'ip_address' => request()->ip(),
      'execution_time' => defined('LARAVEL_START') ? microtime(true) - LARAVEL_START : 0,
      'memory_usage' => memory_get_usage(true)
    ]);
  }

  public static function logWarning($dataSourceId, $message, $details = [])
  {
    return self::create([
      'data_source_id' => $dataSourceId,
      'type' => 'warning',
      'message' => $message,
      'details' => $details,
      'user_id' => Auth::check() ? Auth::user()->getAuthIdentifier() : null,
      'ip_address' => request()->ip(),
      'execution_time' => defined('LARAVEL_START') ? microtime(true) - LARAVEL_START : 0,
      'memory_usage' => memory_get_usage(true)
    ]);
  }

  public function getFormattedExecutionTime()
  {
    return number_format($this->execution_time, 4) . ' seconds';
  }

  public function getFormattedMemoryUsage()
  {
    return round($this->memory_usage / 1024 / 1024, 2) . ' MB';
  }

  public function shouldNotify()
  {
    // Logic to determine if this log entry should trigger a notification
    return $this->type === 'error' ||
      ($this->type === 'warning' && $this->isSignificant());
  }

  protected function isSignificant()
  {
    // Logic to determine if a warning is significant enough for notification
    return false;
  }
}
