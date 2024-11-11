<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiConfiguration extends Model
{
  protected $fillable = [
    'integration_id',
    'api_key',
    'api_secret',
    'endpoint_url',
    'auth_type',
    'headers',
    'additional_params'
  ];

  protected $casts = [
    'headers' => 'json',
    'additional_params' => 'json'
  ];

  protected $hidden = [
    'api_key',
    'api_secret'
  ];

  public function integration()
  {
    return $this->belongsTo(DataIntegration::class);
  }
}
