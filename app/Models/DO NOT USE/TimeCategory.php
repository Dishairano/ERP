<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeCategory extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'color',
    'description',
    'is_billable'
  ];

  protected $casts = [
    'is_billable' => 'boolean',
  ];

  public function timeRegistrations()
  {
    return $this->hasMany(TimeRegistration::class);
  }

  public function getFormattedColorAttribute()
  {
    return $this->color ?? '#6c757d';
  }

  public function scopeOnlyBillable($query)
  {
    return $query->where('is_billable', true);
  }

  public function scopeOnlyNonBillable($query)
  {
    return $query->where('is_billable', false);
  }
}
