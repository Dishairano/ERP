<?php

namespace App\Models;

use App\Traits\NotifiesOnDatabaseChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
  use SoftDeletes, NotifiesOnDatabaseChanges;

  protected $fillable = [
    'user_id',
    'leave_type_id',
    'start_date',
    'end_date',
    'status',
    'reason',
    'comments'
  ];

  protected $casts = [
    'start_date' => 'datetime',
    'end_date' => 'datetime'
  ];

  /**
   * Get the user that owns the leave request
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Get the leave type
   */
  public function leaveType(): BelongsTo
  {
    return $this->belongsTo(LeaveType::class);
  }

  /**
   * Override notification config for specific actions
   */
  public function getNotificationConfig(string $action): ?array
  {
    $leaveType = $this->leaveType?->name ?? 'Leave';
    $userName = $this->user?->name ?? 'Someone';

    switch ($action) {
      case 'created':
        return [
          'title' => 'New Leave Request',
          'body' => "$userName has requested $leaveType from {$this->start_date->format('M d')} to {$this->end_date->format('M d')}",
          'data' => [
            'leave_request_id' => $this->id,
            'action' => 'created'
          ]
        ];

      case 'updated':
        if ($this->wasChanged('status')) {
          return [
            'title' => 'Leave Request Updated',
            'body' => "Your $leaveType request has been {$this->status}",
            'data' => [
              'leave_request_id' => $this->id,
              'action' => 'status_updated',
              'status' => $this->status
            ]
          ];
        }
        return null; // Don't notify for other updates

      case 'deleted':
        return [
          'title' => 'Leave Request Cancelled',
          'body' => "A $leaveType request has been cancelled",
          'data' => [
            'leave_request_id' => $this->id,
            'action' => 'cancelled'
          ]
        ];
    }

    return null;
  }

  /**
   * Override recipients for notifications
   */
  public function getNotificationRecipients(string $action): array
  {
    // Always notify the leave request owner
    $recipients = [$this->user_id];

    // If it's a new request or status update, also notify HR managers
    if (in_array($action, ['created', 'updated'])) {
      $hrManagerIds = User::role('HR Manager')->pluck('id')->toArray();
      $recipients = array_merge($recipients, $hrManagerIds);
    }

    return array_unique($recipients);
  }
}
