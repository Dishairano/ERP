<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplianceTraining extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'title',
    'description',
    'training_type',
    'due_date',
    'status',
    'content',
    'department',
    'is_mandatory',
    'duration_minutes'
  ];

  protected $casts = [
    'due_date' => 'date',
    'is_mandatory' => 'boolean',
    'duration_minutes' => 'integer'
  ];

  public function completions()
  {
    return $this->hasMany(ComplianceTrainingCompletion::class);
  }
}
