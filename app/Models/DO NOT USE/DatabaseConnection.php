<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatabaseConnection extends Model
{
  protected $fillable = [
    'integration_id',
    'driver',
    'host',
    'database',
    'username',
    'password',
    'port',
    'additional_config'
  ];

  protected $hidden = [
    'password'
  ];

  protected $casts = [
    'additional_config' => 'json'
  ];

  public function integration()
  {
    return $this->belongsTo(DataIntegration::class);
  }
}
