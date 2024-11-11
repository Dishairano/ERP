<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiReport extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'description',
    'kpi_definitions',
    'filters',
    'visualization_settings',
    'frequency',
    'recipients',
    'created_by'
  ];

  protected $casts = [
    'kpi_definitions' => 'array',
    'filters' => 'array',
    'visualization_settings' => 'array',
    'recipients' => 'array'
  ];

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function exports()
  {
    return $this->hasMany(KpiReportExport::class);
  }

  public function definitions()
  {
    return KpiDefinition::whereIn('id', $this->kpi_definitions);
  }
}
