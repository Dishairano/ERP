<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaPlatform extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'api_key',
    'api_secret',
    'access_token',
    'refresh_token',
    'settings',
    'status'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'settings' => 'array'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'api_key',
    'api_secret',
    'access_token',
    'refresh_token'
  ];

  /**
   * Get the posts for this platform.
   */
  public function posts()
  {
    return $this->hasMany(SocialMediaPost::class);
  }

  /**
   * Get all of the platform's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the platform's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
