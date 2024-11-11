<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralLedger extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'account_id',
    'journal_id',
    'date',
    'description',
    'debit',
    'credit',
    'reference',
    'notes',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'date' => 'date',
    'debit' => 'decimal:2',
    'credit' => 'decimal:2'
  ];

  /**
   * Get the account for this entry.
   */
  public function account()
  {
    return $this->belongsTo(Account::class);
  }

  /**
   * Get the journal for this entry.
   */
  public function journal()
  {
    return $this->belongsTo(Journal::class);
  }

  /**
   * Get the user who created the entry.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the entry's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the entry's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
