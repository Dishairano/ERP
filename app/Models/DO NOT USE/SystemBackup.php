<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemBackup extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'backup_name',
    'file_path',
    'backup_type',
    'file_size',
    'status',
    'error_message',
    'included_tables',
    'excluded_tables',
    'backup_metadata',
    'started_at',
    'completed_at',
    'initiated_by'
  ];

  protected $casts = [
    'included_tables' => 'array',
    'excluded_tables' => 'array',
    'backup_metadata' => 'array',
    'started_at' => 'datetime',
    'completed_at' => 'datetime'
  ];

  public function initiator()
  {
    return $this->belongsTo(User::class, 'initiated_by');
  }

  public function restorations()
  {
    return $this->hasMany(BackupRestoration::class);
  }

  public function getBackupSizeFormatted()
  {
    $size = $this->file_size;
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    while ($size >= 1024 && $i < count($units) - 1) {
      $size /= 1024;
      $i++;
    }
    return round($size, 2) . ' ' . $units[$i];
  }

  public function getDurationInMinutes()
  {
    if ($this->started_at && $this->completed_at) {
      return $this->started_at->diffInMinutes($this->completed_at);
    }
    return null;
  }
}
