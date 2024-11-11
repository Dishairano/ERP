<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingAnalytics extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'campaign_id',
    'metrics',
    'period',
    'start_date',
    'end_date',
    'roi',
    'conversion_rate',
    'cost_per_lead',
    'cost_per_acquisition'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'metrics' => 'array',
    'start_date' => 'date',
    'end_date' => 'date',
    'roi' => 'decimal:2',
    'conversion_rate' => 'decimal:2',
    'cost_per_lead' => 'decimal:2',
    'cost_per_acquisition' => 'decimal:2'
  ];

  /**
   * Get the campaign these analytics belong to.
   */
  public function campaign()
  {
    return $this->belongsTo(Campaign::class);
  }

  /**
   * Get all of the analytics' notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the analytics' activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
