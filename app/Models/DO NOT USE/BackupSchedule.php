<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BackupSchedule extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'frequency',
    'backup_type',
    'scheduled_time',
    'is_active',
    'configuration',
    'last_run',
    'next_run',
    'created_by'
  ];

  protected $casts = [
    'configuration' => 'array',
    'is_active' => 'boolean',
    'last_run' => 'datetime',
    'next_run' => 'datetime',
    'scheduled_time' => 'datetime'
  ];

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function calculateNextRun()
  {
    $now = now();
    $time = $this->scheduled_time;

    switch ($this->frequency) {
      case 'daily':
        $next = $now->copy()->setTimeFromTimeString($time);
        if ($next->isPast()) {
          $next->addDay();
        }
        break;
      case 'weekly':
        $next = $now->copy()->setTimeFromTimeString($time);
        if ($next->isPast()) {
          $next->addWeek();
        }
        break;
      case 'monthly':
        $next = $now->copy()->setTimeFromTimeString($time);
        if ($next->isPast()) {
          $next->addMonth();
        }
        break;
      default:
        return null;
    }

    return $next;
  }
}
