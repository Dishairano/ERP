<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'title',
    'contact_id',
    'lead_id',
    'stage',
    'status',
    'value',
    'probability',
    'expected_close_date',
    'actual_close_date',
    'source',
    'description',
    'assigned_to',
    'lost_reason'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'value' => 'decimal:2',
    'probability' => 'integer',
    'expected_close_date' => 'date',
    'actual_close_date' => 'date'
  ];

  /**
   * Get the contact associated with the opportunity.
   */
  public function contact()
  {
    return $this->belongsTo(Contact::class);
  }

  /**
   * Get the lead associated with the opportunity.
   */
  public function lead()
  {
    return $this->belongsTo(Lead::class);
  }

  /**
   * Get the user assigned to the opportunity.
   */
  public function assignedTo()
  {
    return $this->belongsTo(User::class, 'assigned_to');
  }

  /**
   * Get all of the opportunity's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the opportunity's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
