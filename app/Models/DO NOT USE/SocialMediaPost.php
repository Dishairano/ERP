<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaPost extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'platform_id',
    'campaign_id',
    'content',
    'media',
    'scheduled_at',
    'published_at',
    'target_audience',
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
    'media' => 'array',
    'target_audience' => 'array',
    'metrics' => 'array',
    'scheduled_at' => 'datetime',
    'published_at' => 'datetime'
  ];

  /**
   * Get the platform this post belongs to.
   */
  public function platform()
  {
    return $this->belongsTo(SocialMediaPlatform::class);
  }

  /**
   * Get the campaign this post belongs to.
   */
  public function campaign()
  {
    return $this->belongsTo(Campaign::class);
  }

  /**
   * Get the user who created the post.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the post's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the post's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
