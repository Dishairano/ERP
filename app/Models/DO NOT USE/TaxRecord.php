<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRecord extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'type_id',
    'period_id',
    'amount',
    'due_date',
    'filing_date',
    'status',
    'notes',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'amount' => 'decimal:2',
    'due_date' => 'date',
    'filing_date' => 'date'
  ];

  /**
   * Get the type for this tax record.
   */
  public function type()
  {
    return $this->belongsTo(TaxType::class, 'type_id');
  }

  /**
   * Get the period for this tax record.
   */
  public function period()
  {
    return $this->belongsTo(TaxPeriod::class, 'period_id');
  }

  /**
   * Get the user who created the record.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the record's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the record's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
