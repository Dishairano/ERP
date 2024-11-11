<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierNotification extends Model
{
  use HasFactory;

  protected $fillable = [
    'supplier_id',
    'type',
    'title',
    'message',
    'data',
    'read_at',
    'priority'
  ];

  protected $casts = [
    'data' => 'array',
    'read_at' => 'datetime',
  ];

  // Notification types
  const TYPE_CONTRACT_EXPIRING = 'contract_expiring';
  const TYPE_PERFORMANCE_ALERT = 'performance_alert';
  const TYPE_FEEDBACK_REQUIRED = 'feedback_required';
  const TYPE_CONTRACT_VIOLATION = 'contract_violation';
  const TYPE_PAYMENT_DUE = 'payment_due';

  // Priority levels
  const PRIORITY_LOW = 'low';
  const PRIORITY_MEDIUM = 'medium';
  const PRIORITY_HIGH = 'high';

  public function supplier()
  {
    return $this->belongsTo(Supplier::class);
  }

  public function markAsRead()
  {
    $this->update(['read_at' => now()]);
  }

  public function isRead()
  {
    return !is_null($this->read_at);
  }

  // Scopes
  public function scopeUnread($query)
  {
    return $query->whereNull('read_at');
  }

  public function scopeByType($query, $type)
  {
    return $query->where('type', $type);
  }

  public function scopeByPriority($query, $priority)
  {
    return $query->where('priority', $priority);
  }

  // Helper methods for creating specific notifications
  public static function createContractExpiringNotification($contract)
  {
    $daysUntilExpiry = now()->diffInDays($contract->end_date);
    $priority = $daysUntilExpiry <= 7 ? self::PRIORITY_HIGH : ($daysUntilExpiry <= 30 ? self::PRIORITY_MEDIUM : self::PRIORITY_LOW);

    return self::create([
      'supplier_id' => $contract->supplier_id,
      'type' => self::TYPE_CONTRACT_EXPIRING,
      'title' => 'Contract Verloopt Binnenkort',
      'message' => "Contract {$contract->contract_number} verloopt over {$daysUntilExpiry} dagen",
      'data' => [
        'contract_id' => $contract->id,
        'contract_number' => $contract->contract_number,
        'expiry_date' => $contract->end_date,
        'days_until_expiry' => $daysUntilExpiry
      ],
      'priority' => $priority
    ]);
  }

  public static function createPerformanceAlertNotification($supplier, $metric, $score, $threshold)
  {
    return self::create([
      'supplier_id' => $supplier->id,
      'type' => self::TYPE_PERFORMANCE_ALERT,
      'title' => 'Performance Waarschuwing',
      'message' => "Performance score voor {$metric} is onder de drempelwaarde ({$score}%)",
      'data' => [
        'metric' => $metric,
        'score' => $score,
        'threshold' => $threshold
      ],
      'priority' => self::PRIORITY_HIGH
    ]);
  }

  public static function createFeedbackRequiredNotification($order)
  {
    return self::create([
      'supplier_id' => $order->supplier_id,
      'type' => self::TYPE_FEEDBACK_REQUIRED,
      'title' => 'Feedback Gevraagd',
      'message' => "Geef feedback voor order #{$order->order_number}",
      'data' => [
        'order_id' => $order->id,
        'order_number' => $order->order_number,
        'order_date' => $order->created_at
      ],
      'priority' => self::PRIORITY_MEDIUM
    ]);
  }

  public static function createContractViolationNotification($supplier, $violation)
  {
    return self::create([
      'supplier_id' => $supplier->id,
      'type' => self::TYPE_CONTRACT_VIOLATION,
      'title' => 'Contract Voorwaarden Overtreding',
      'message' => "Leverancier voldoet niet aan contractvoorwaarden: {$violation}",
      'data' => [
        'violation_type' => $violation,
        'detected_at' => now()
      ],
      'priority' => self::PRIORITY_HIGH
    ]);
  }

  public static function createPaymentDueNotification($invoice)
  {
    $daysUntilDue = now()->diffInDays($invoice->due_date);
    $priority = $daysUntilDue <= 3 ? self::PRIORITY_HIGH : ($daysUntilDue <= 7 ? self::PRIORITY_MEDIUM : self::PRIORITY_LOW);

    return self::create([
      'supplier_id' => $invoice->supplier_id,
      'type' => self::TYPE_PAYMENT_DUE,
      'title' => 'Betaling Vervalt Binnenkort',
      'message' => "Factuur #{$invoice->number} moet binnen {$daysUntilDue} dagen betaald worden",
      'data' => [
        'invoice_id' => $invoice->id,
        'invoice_number' => $invoice->number,
        'amount' => $invoice->amount,
        'due_date' => $invoice->due_date
      ],
      'priority' => $priority
    ]);
  }
}
