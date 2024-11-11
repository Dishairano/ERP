<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'title',
    'content',
    'notable_type',
    'notable_id',
    'created_by'
  ];

  /**
   * Get the owning notable model.
   */
  public function notable()
  {
    return $this->morphTo();
  }

  /**
   * Get the user who created the note.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }
}
