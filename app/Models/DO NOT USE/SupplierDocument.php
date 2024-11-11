<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierDocument extends Model
{
  use HasFactory;

  protected $fillable = [
    'supplier_id',
    'title',
    'file_path',
    'document_type',
    'description',
    'valid_until'
  ];

  protected $casts = [
    'valid_until' => 'date',
  ];

  public function supplier()
  {
    return $this->belongsTo(Supplier::class);
  }

  public function isExpired()
  {
    return $this->valid_until && $this->valid_until->isPast();
  }

  public function isExpiring($days = 30)
  {
    return $this->valid_until && $this->valid_until->diffInDays(now()) <= $days;
  }
}
