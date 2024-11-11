<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
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
    'source',
    'schema',
    'metadata',
    'status',
    'last_updated_at',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'schema' => 'array',
    'metadata' => 'array',
    'last_updated_at' => 'datetime'
  ];

  /**
   * Get the data analyses using this dataset.
   */
  public function dataAnalyses()
  {
    return $this->hasMany(DataAnalysis::class);
  }

  /**
   * Get the data visualizations using this dataset.
   */
  public function dataVisualizations()
  {
    return $this->hasMany(DataVisualization::class);
  }

  /**
   * Get the predictive models using this dataset.
   */
  public function predictiveModels()
  {
    return $this->hasMany(PredictiveModel::class);
  }

  /**
   * Get the data mining jobs using this dataset.
   */
  public function dataMiningJobs()
  {
    return $this->hasMany(DataMiningJob::class);
  }

  /**
   * Get the user who created the dataset.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the dataset's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the dataset's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
