<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataExport extends Model
{
  protected $fillable = [
    'result_id',
    'format',
    'file_path'
  ];

  public function result(): BelongsTo
  {
    return $this->belongsTo(DataAnalysisResult::class, 'result_id');
  }
}
