<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
  protected $fillable = [
    'project_id',
    'title',
    'description',
    'file_path',
    'file_type',
    'file_size',
    'version',
    'status',
    'uploaded_by',
    'last_modified_by',
    'category'
  ];

  protected $casts = [
    'file_size' => 'integer',
    'version' => 'float'
  ];

  public function project()
  {
    return $this->belongsTo(CoreProjectDashboardModal::class, 'project_id');
  }

  public function uploader()
  {
    return $this->belongsTo(User::class, 'uploaded_by');
  }

  public function modifier()
  {
    return $this->belongsTo(User::class, 'last_modified_by');
  }

  public function getFileExtensionAttribute()
  {
    return pathinfo($this->file_path, PATHINFO_EXTENSION);
  }

  public function getFormattedFileSizeAttribute()
  {
    $bytes = $this->file_size;
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, 2) . ' ' . $units[$pow];
  }
}
