<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingRecord extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'employee_id',
    'training_id',
    'start_date',
    'completion_date',
    'status',
    'score',
    'certificate_number',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'start_date' => 'date',
    'completion_date' => 'date',
    'score' => 'decimal:2'
  ];

  /**
   * Get the employee that owns the training record.
   */
  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }

  /**
   * Get the training that owns the record.
   */
  public function training()
  {
    return $this->belongsTo(Training::class);
  }
}
