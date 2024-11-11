<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplianceDocument extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'title',
    'document_type',
    'file_path',
    'expiry_date',
    'status',
    'description',
    'department',
    'owner',
    'tags'
  ];

  protected $casts = [
    'expiry_date' => 'date',
    'tags' => 'array'
  ];

  public function requirement()
  {
    return $this->belongsTo(ComplianceRequirement::class);
  }

  public function audit()
  {
    return $this->belongsTo(ComplianceAudit::class);
  }
}
