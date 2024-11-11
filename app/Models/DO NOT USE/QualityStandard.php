<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityStandard extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'product_id',
    'name',
    'description',
    'specifications',
    'version',
    'effective_date',
    'status',
    'approver_id'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'specifications' => 'array',
    'effective_date' => 'date'
  ];

  /**
   * Get the product this standard applies to.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Get the user who approved this standard.
   */
  public function approver()
  {
    return $this->belongsTo(User::class, 'approver_id');
  }

  /**
   * Get all of the standard's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the standard's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
