<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'contact_id',
    'type',
    'subject',
    'content',
    'direction',
    'status',
    'sent_at',
    'sent_by',
    'received_at',
    'channel',
    'reference_type',
    'reference_id'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'sent_at' => 'datetime',
    'received_at' => 'datetime'
  ];

  /**
   * Get the contact associated with the communication.
   */
  public function contact()
  {
    return $this->belongsTo(Contact::class);
  }

  /**
   * Get the user who sent the communication.
   */
  public function sender()
  {
    return $this->belongsTo(User::class, 'sent_by');
  }

  /**
   * Get the reference model (polymorphic).
   */
  public function reference()
  {
    return $this->morphTo();
  }
}
