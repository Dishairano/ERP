<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogisticsManagement extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'logistics_management';

  protected $fillable = [
    'shipment_number',
    'origin',
    'destination',
    'status',
    'estimated_delivery_date',
    'actual_delivery_date',
    'tracking_number',
    'carrier',
    'notes'
  ];

  protected $casts = [
    'estimated_delivery_date' => 'date',
    'actual_delivery_date' => 'date',
  ];
}
