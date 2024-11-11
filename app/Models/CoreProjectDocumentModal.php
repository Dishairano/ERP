<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreProjectDocumentModal extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'project_documents';

  protected $fillable = [
    'project_id',
    'title',
    'description',
    'file_path',
    'file_type',
    'file_size',
    'uploaded_by',
    'version'
  ];

  protected $casts = [
    'file_size' => 'integer',
    'version' => 'integer'
  ];

  public function project()
  {
    return $this->belongsTo(CoreProjectModal::class, 'project_id');
  }

  public function uploader()
  {
    return $this->belongsTo(User::class, 'uploaded_by');
  }

  public function getFileExtensionAttribute()
  {
    return pathinfo($this->file_path, PATHINFO_EXTENSION);
  }

  public function getFormattedFileSizeAttribute()
  {
    $bytes = $this->file_size;
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $index = 0;

    while ($bytes >= 1024 && $index < count($units) - 1) {
      $bytes /= 1024;
      $index++;
    }

    return round($bytes, 2) . ' ' . $units[$index];
  }
}
