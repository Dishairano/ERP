<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dashboard extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'slug',
    'description',
    'layout',
    'widgets',
    'is_default',
    'is_public',
    'created_by'
  ];

  protected $casts = [
    'layout' => 'json',
    'widgets' => 'json',
    'is_default' => 'boolean',
    'is_public' => 'boolean'
  ];

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }
}
