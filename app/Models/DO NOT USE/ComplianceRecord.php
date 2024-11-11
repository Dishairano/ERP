<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplianceRecord extends Model
{
  protected $fillable = [
    'compliance_type',
    'status',
    'details',
    'checked_at',
    'checked_by'
  ];

  protected $casts = [
    'details' => 'json',
    'checked_at' => 'datetime'
  ];

  public function checker()
  {
    return $this->belongsTo(User::class, 'checked_by');
  }

  public static function recordCheck($type, $status, $details, $userId)
  {
    return static::create([
      'compliance_type' => $type,
      'status' => $status,
      'details' => $details,
      'checked_at' => now(),
      'checked_by' => $userId
    ]);
  }
}
