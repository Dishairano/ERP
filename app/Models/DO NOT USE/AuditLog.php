<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
  use HasFactory;

  protected $fillable = [
    'event_type',
    'auditable_type',
    'auditable_id',
    'user_id',
    'ip_address',
    'user_agent',
    'old_values',
    'new_values',
    'metadata',
    'description'
  ];

  protected $casts = [
    'old_values' => 'array',
    'new_values' => 'array',
    'metadata' => 'array'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function auditable()
  {
    return $this->morphTo();
  }

  public function getChangesAttribute()
  {
    $changes = [];

    if (!empty($this->old_values) && !empty($this->new_values)) {
      foreach ($this->new_values as $key => $value) {
        if (isset($this->old_values[$key]) && $this->old_values[$key] !== $value) {
          $changes[$key] = [
            'old' => $this->old_values[$key],
            'new' => $value
          ];
        }
      }
    }

    return $changes;
  }

  public static function log($event_type, $auditable, $description = null, $metadata = [])
  {
    return static::create([
      'event_type' => $event_type,
      'auditable_type' => get_class($auditable),
      'auditable_id' => $auditable->id,
      'user_id' => auth()->id(),
      'ip_address' => request()->ip(),
      'user_agent' => request()->userAgent(),
      'description' => $description,
      'metadata' => $metadata
    ]);
  }
}
