<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'phone',
    'company',
    'position',
    'type',
    'status',
    'source',
    'notes',
    'contactable_type',
    'contactable_id'
  ];

  /**
   * Get the contact's full name.
   *
   * @return string
   */
  public function getFullNameAttribute()
  {
    return "{$this->first_name} {$this->last_name}";
  }

  /**
   * Get the owning contactable model.
   */
  public function contactable()
  {
    return $this->morphTo();
  }

  /**
   * Get all of the contact's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the contact's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }

  /**
   * Get all of the contact's communications.
   */
  public function communications()
  {
    return $this->hasMany(Communication::class);
  }

  /**
   * Get all of the contact's opportunities.
   */
  public function opportunities()
  {
    return $this->hasMany(Opportunity::class);
  }
}
