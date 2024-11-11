<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisition extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'department_id',
    'requester_id',
    'required_date',
    'priority',
    'reason',
    'status',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'required_date' => 'date'
  ];

  /**
   * Get the department that made the requisition.
   */
  public function department()
  {
    return $this->belongsTo(Department::class);
  }

  /**
   * Get the user who requested the requisition.
   */
  public function requester()
  {
    return $this->belongsTo(User::class, 'requester_id');
  }

  /**
   * Get the items for this requisition.
   */
  public function items()
  {
    return $this->hasMany(PurchaseRequisitionItem::class);
  }

  /**
   * Get all of the requisition's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the requisition's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
