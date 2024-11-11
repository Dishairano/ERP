<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComplianceReport extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'type',
    'content',
    'generated_by',
    'format',
    'status',
    'generated_at',
    'distribution_list'
  ];

  protected $casts = [
    'content' => 'json',
    'distribution_list' => 'json',
    'generated_at' => 'datetime'
  ];

  public function generator()
  {
    return $this->belongsTo(User::class, 'generated_by');
  }

  public function isDraft()
  {
    return $this->status === 'Draft';
  }

  public function isFinal()
  {
    return $this->status === 'Final';
  }

  public function isArchived()
  {
    return $this->status === 'Archived';
  }

  public function canBeEdited()
  {
    return $this->isDraft();
  }

  public function shouldBeDistributed()
  {
    return $this->isFinal() && !empty($this->distribution_list);
  }
}
