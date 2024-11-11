<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityBackupLog extends Model
{
  protected $fillable = [
    'backup_type',
    'status',
    'file_path',
    'size_bytes',
    'encryption_details',
    'completed_at'
  ];

  protected $casts = [
    'completed_at' => 'datetime'
  ];

  public function isSuccessful()
  {
    return $this->status === 'completed' && $this->completed_at !== null;
  }

  public static function logBackup($type, $status, $filePath = null, $size = null, $encryptionDetails = null)
  {
    return static::create([
      'backup_type' => $type,
      'status' => $status,
      'file_path' => $filePath,
      'size_bytes' => $size,
      'encryption_details' => $encryptionDetails,
      'completed_at' => $status === 'completed' ? now() : null
    ]);
  }
}
