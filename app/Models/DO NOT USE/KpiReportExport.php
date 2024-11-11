<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiReportExport extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'kpi_report_id',
    'format',
    'file_path',
    'created_by'
  ];

  public function report()
  {
    return $this->belongsTo(KpiReport::class, 'kpi_report_id');
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }
}
