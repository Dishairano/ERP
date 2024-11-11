<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataValidationRule extends Model
{
  protected $fillable = [
    'integration_id',
    'field_name',
    'rule_type',
    'rule_config',
    'error_message'
  ];

  protected $casts = [
    'rule_config' => 'json'
  ];

  public function integration()
  {
    return $this->belongsTo(DataIntegration::class);
  }
}
