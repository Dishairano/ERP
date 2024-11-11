<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLogArchive extends Model
{
  use HasFactory;

  protected $fillable = [
    'archive_date',
    'records_count',
    'file_path',
    'status',
    'error_message',
    'created_by'
  ];

  protected $casts = [
    'archive_date' => 'date'
  ];

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function getArchiveSize()
  {
    if (file_exists($this->file_path)) {
      $size = filesize($this->file_path);
      $units = ['B', 'KB', 'MB', 'GB', 'TB'];
      $i = 0;
      while ($size >= 1024 && $i < count($units) - 1) {
        $size /= 1024;
        $i++;
      }
      return round($size, 2) . ' ' . $units[$i];
    }
    return '0 B';
  }
}
