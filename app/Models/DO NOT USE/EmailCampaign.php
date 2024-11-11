<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'subject',
    'template_id',
    'campaign_id',
    'content',
    'scheduled_at',
    'sent_at',
    'status',
    'created_by',
    'metrics'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'scheduled_at' => 'datetime',
    'sent_at' => 'datetime',
    'metrics' => 'array'
  ];

  /**
   * Get the campaign this email campaign belongs to.
   */
  public function campaign()
  {
    return $this->belongsTo(Campaign::class);
  }

  /**
   * Get the template used for this email campaign.
   */
  public function template()
  {
    return $this->belongsTo(EmailTemplate::class);
  }

  /**
   * Get the segments targeted by this email campaign.
   */
  public function segments()
  {
    return $this->belongsToMany(CustomerSegment::class);
  }

  /**
   * Get the user who created the email campaign.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the campaign's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the campaign's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
