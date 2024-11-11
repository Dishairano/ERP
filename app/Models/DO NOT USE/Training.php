<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'description',
    'type',
    'duration',
    'provider',
    'cost',
    'requirements',
    'status'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'requirements' => 'array',
    'cost' => 'decimal:2'
  ];

  /**
   * Get the training records for the training.
   */
  public function trainingRecords()
  {
    return $this->hasMany(TrainingRecord::class);
  }

  /**
   * Get the employees who have taken this training.
   */
  public function employees()
  {
    return $this->belongsToMany(Employee::class, 'training_records')
      ->withPivot('start_date', 'completion_date', 'status', 'score', 'certificate_number')
      ->withTimestamps();
  }
}
