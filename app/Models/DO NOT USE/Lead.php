<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'title',
    'company_name',
    'industry',
    'estimated_value',
    'source',
    'status',
    'assigned_to',
    'expected_close_date',
    'description',
    'requirements',
    'probability'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'estimated_value' => 'decimal:2',
    'probability' => 'integer',
    'expected_close_date' => 'date',
    'requirements' => 'array'
  ];

  /**
   * Get the contacts for the lead.
   */
  public function contacts()
  {
    return $this->morphMany(Contact::class, 'contactable');
  }

  /**
   * Get the user that the lead is assigned to.
   */
  public function assignedTo()
  {
    return $this->belongsTo(User::class, 'assigned_to');
  }

  /**
   * Get all of the lead's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the lead's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }

  /**
   * Get the opportunities associated with the lead.
   */
  public function opportunities()
  {
    return $this->hasMany(Opportunity::class);
  }
}
