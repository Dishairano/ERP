<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'code',
    'department_id',
    'description',
    'requirements',
    'responsibilities',
    'salary_range_min',
    'salary_range_max',
    'status'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'requirements' => 'array',
    'responsibilities' => 'array',
    'salary_range_min' => 'decimal:2',
    'salary_range_max' => 'decimal:2'
  ];

  /**
   * Get the department that owns the position.
   */
  public function department()
  {
    return $this->belongsTo(Department::class);
  }

  /**
   * Get the employees for the position.
   */
  public function employees()
  {
    return $this->hasMany(Employee::class);
  }
}
