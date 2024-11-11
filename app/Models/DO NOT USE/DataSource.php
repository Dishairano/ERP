<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DataSource extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'type', // database, api, cache, file, custom
    'configuration',
    'credentials',
    'refresh_interval',
    'last_refresh',
    'status',
    'validation_rules',
    'transformation_rules',
    'error_handling',
    'retry_settings',
    'is_active'
  ];

  protected $casts = [
    'configuration' => 'json',
    'credentials' => 'encrypted:json',
    'validation_rules' => 'json',
    'transformation_rules' => 'json',
    'error_handling' => 'json',
    'retry_settings' => 'json',
    'is_active' => 'boolean',
    'last_refresh' => 'datetime'
  ];

  // Relationships
  public function components()
  {
    return $this->hasMany(DashboardComponent::class);
  }

  public function dataLogs()
  {
    return $this->hasMany(DataSourceLog::class);
  }

  // Scopes
  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  public function scopeByType($query, $type)
  {
    return $query->where('type', $type);
  }

  // Methods
  public function fetchData($params = [])
  {
    try {
      $data = match ($this->type) {
        'database' => $this->fetchFromDatabase($params),
        'api' => $this->fetchFromApi($params),
        'cache' => $this->fetchFromCache($params),
        'file' => $this->fetchFromFile($params),
        'custom' => $this->fetchFromCustomSource($params),
        default => throw new \Exception("Unsupported data source type: {$this->type}")
      };

      $this->logSuccess();
      return $this->transformData($data);
    } catch (\Exception $e) {
      $this->logError($e);
      $this->handleError($e);
      throw $e;
    }
  }

  protected function fetchFromDatabase($params)
  {
    $config = $this->configuration;
    $query = DB::connection($config['connection'] ?? null)
      ->table($config['table'])
      ->select($config['columns'] ?? '*');

    if (isset($config['where'])) {
      foreach ($config['where'] as $condition) {
        $query->where(...$condition);
      }
    }

    return $query->get();
  }

  protected function fetchFromApi($params)
  {
    $config = $this->configuration;
    $response = Http::withHeaders($config['headers'] ?? [])
      ->withToken($this->credentials['api_key'] ?? null)
      ->get($config['url'], $params);

    if (!$response->successful()) {
      throw new \Exception("API request failed: " . $response->body());
    }

    return $response->json();
  }

  protected function fetchFromCache($params)
  {
    $key = $this->configuration['cache_key'] ?? $this->name;
    return Cache::get($key);
  }

  protected function fetchFromFile($params)
  {
    $path = $this->configuration['file_path'];
    if (!file_exists($path)) {
      throw new \Exception("File not found: {$path}");
    }
    return file_get_contents($path);
  }

  protected function fetchFromCustomSource($params)
  {
    // Implementation for custom data source types
    $handler = $this->configuration['handler_class'];
    if (!class_exists($handler)) {
      throw new \Exception("Custom handler class not found: {$handler}");
    }
    return (new $handler)->fetch($params);
  }

  protected function transformData($data)
  {
    if (empty($this->transformation_rules)) {
      return $data;
    }

    foreach ($this->transformation_rules as $rule) {
      $data = match ($rule['type']) {
        'filter' => $this->applyFilter($data, $rule),
        'map' => $this->applyMap($data, $rule),
        'reduce' => $this->applyReduce($data, $rule),
        'sort' => $this->applySort($data, $rule),
        default => $data
      };
    }

    return $data;
  }

  protected function validateData($data)
  {
    if (empty($this->validation_rules)) {
      return true;
    }

    // Implement validation logic based on rules
    foreach ($this->validation_rules as $rule) {
      // Apply validation rules
    }

    return true;
  }

  protected function logSuccess()
  {
    $this->update(['last_refresh' => now(), 'status' => 'success']);
    $this->dataLogs()->create([
      'type' => 'success',
      'message' => 'Data fetched successfully'
    ]);
  }

  protected function logError(\Exception $e)
  {
    $this->update(['status' => 'error']);
    $this->dataLogs()->create([
      'type' => 'error',
      'message' => $e->getMessage(),
      'stack_trace' => $e->getTraceAsString()
    ]);
  }

  protected function handleError(\Exception $e)
  {
    $errorHandling = $this->error_handling;

    if (!empty($errorHandling['retry']) && $errorHandling['retry']['enabled']) {
      // Implement retry logic
    }

    if (!empty($errorHandling['fallback']) && $errorHandling['fallback']['enabled']) {
      // Implement fallback logic
    }

    if (!empty($errorHandling['notification']) && $errorHandling['notification']['enabled']) {
      // Send notification
    }
  }

  // Helper methods for data transformation
  protected function applyFilter($data, $rule)
  {
    // Implementation
  }

  protected function applyMap($data, $rule)
  {
    // Implementation
  }

  protected function applyReduce($data, $rule)
  {
    // Implementation
  }

  protected function applySort($data, $rule)
  {
    // Implementation
  }
}
