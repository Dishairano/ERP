<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistributionPlanning extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'plan_name',
    'description',
    'start_date',
    'end_date',
    'status'
  ];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date'
  ];
}
