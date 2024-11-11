<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
  protected $fillable = [
    'integration_id',
    'status',
    'message',
    'records_processed',
    'records_succeeded',
    'records_failed',
    'error_details'
  ];

  protected $casts = [
    'error_details' => 'json'
  ];

  public function integration()
  {
    return $this->belongsTo(DataIntegration::class);
  }
}
