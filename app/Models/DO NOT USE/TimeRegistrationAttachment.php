<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeRegistrationAttachment extends Model
{
  use HasFactory;

  protected $fillable = [
    'time_registration_id',
    'file_name',
    'file_path',
    'file_type',
    'file_size'
  ];

  public function timeRegistration()
  {
    return $this->belongsTo(TimeRegistration::class);
  }
}
