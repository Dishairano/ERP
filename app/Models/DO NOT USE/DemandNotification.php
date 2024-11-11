<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandNotification extends Model
{
  protected $fillable = [
    'notification_type',
    'title',
    'message',
    'affected_items',
    'is_read',
    'read_at',
    'user_id'
  ];

  protected $casts = [
    'affected_items' => 'array',
    'is_read' => 'boolean',
    'read_at' => 'datetime'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public static function getNotificationTypes()
  {
    return [
      'forecast_deviation' => 'Forecast Deviation',
      'trend_alert' => 'Market Trend Alert',
      'promotion_start' => 'Promotion Starting',
      'promotion_end' => 'Promotion Ending',
      'accuracy_report' => 'Accuracy Report',
      'budget_alert' => 'Budget Alert'
    ];
  }
}
