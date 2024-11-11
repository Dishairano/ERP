<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceCost extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'resource_id',
    'project_id',
    'cost_type',
    'amount',
    'currency',
    'date',
    'description',
    'status',
  ];

  protected $casts = [
    'date' => 'date',
    'amount' => 'decimal:2',
  ];

  public function resource()
  {
    return $this->belongsTo(Resource::class);
  }

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public static function getTotalCostsByProject($projectId)
  {
    return self::where('project_id', $projectId)
      ->where('status', 'actual')
      ->sum('amount');
  }

  public static function getTotalCostsByResource($resourceId)
  {
    return self::where('resource_id', $resourceId)
      ->where('status', 'actual')
      ->sum('amount');
  }
}
