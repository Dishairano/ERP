<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSegment extends Model
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
    'criteria',
    'status',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'criteria' => 'array'
  ];

  /**
   * Get the customers in this segment.
   */
  public function customers()
  {
    return $this->belongsToMany(Customer::class);
  }

  /**
   * Get the email campaigns targeting this segment.
   */
  public function emailCampaigns()
  {
    return $this->belongsToMany(EmailCampaign::class);
  }

  /**
   * Get the user who created the segment.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the segment's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the segment's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
