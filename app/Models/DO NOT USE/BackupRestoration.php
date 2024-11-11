<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupRestoration extends Model
{
  use HasFactory;

  protected $fillable = [
    'system_backup_id',
    'status',
    'error_message',
    'restored_tables',
    'restoration_metadata',
    'started_at',
    'completed_at',
    'initiated_by'
  ];

  protected $casts = [
    'restored_tables' => 'array',
    'restoration_metadata' => 'array',
    'started_at' => 'datetime',
    'completed_at' => 'datetime'
  ];

  public function systemBackup()
  {
    return $this->belongsTo(SystemBackup::class);
  }

  public function initiator()
  {
    return $this->belongsTo(User::class, 'initiated_by');
  }

  public function getDurationInMinutes()
  {
    if ($this->started_at && $this->completed_at) {
      return $this->started_at->diffInMinutes($this->completed_at);
    }
    return null;
  }
}
