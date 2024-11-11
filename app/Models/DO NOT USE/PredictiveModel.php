<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredictiveModel extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'dataset_id',
    'type',
    'parameters',
    'target_variable',
    'features',
    'description',
    'status',
    'metrics',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'parameters' => 'array',
    'features' => 'array',
    'metrics' => 'array'
  ];

  /**
   * Get the dataset used for this model.
   */
  public function dataset()
  {
    return $this->belongsTo(Dataset::class);
  }

  /**
   * Get the user who created the model.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the model's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the model's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
