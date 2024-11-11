<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataMapping extends Model
{
  protected $fillable = [
    'integration_id',
    'source_field',
    'target_field',
    'target_model',
    'data_type',
    'transformation_rules'
  ];

  protected $casts = [
    'transformation_rules' => 'json'
  ];

  public function integration()
  {
    return $this->belongsTo(DataIntegration::class);
  }
}
