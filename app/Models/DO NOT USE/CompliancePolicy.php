<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompliancePolicy extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'content',
    'category',
    'version',
    'effective_date',
    'review_date',
    'status',
    'approved_by'
  ];

  protected $casts = [
    'effective_date' => 'date',
    'review_date' => 'date',
    'approved_by' => 'json'
  ];

  public function isActive()
  {
    return $this->status === 'Active';
  }

  public function isDraft()
  {
    return $this->status === 'Draft';
  }

  public function isArchived()
  {
    return $this->status === 'Archived';
  }

  public function needsReview()
  {
    return now()->greaterThanOrEqual($this->review_date);
  }

  public function isEffective()
  {
    return now()->greaterThanOrEqual($this->effective_date);
  }

  public function getApprovers()
  {
    return User::whereIn('id', $this->approved_by)->get();
  }
}
