<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierNotification;
use Illuminate\Http\Request;

class SupplierNotificationsController extends Controller
{
  public function index(Supplier $supplier)
  {
    $notifications = $supplier->notifications()
      ->orderBy('created_at', 'desc')
      ->paginate(20);

    $unreadNotifications = $supplier->notifications()
      ->whereNull('read_at')
      ->orderBy('created_at', 'desc')
      ->get();

    $contractNotifications = $supplier->notifications()
      ->where('type', SupplierNotification::TYPE_CONTRACT_EXPIRING)
      ->orderBy('created_at', 'desc')
      ->get();

    $performanceNotifications = $supplier->notifications()
      ->whereIn('type', [
        SupplierNotification::TYPE_PERFORMANCE_ALERT,
        SupplierNotification::TYPE_CONTRACT_VIOLATION
      ])
      ->orderBy('created_at', 'desc')
      ->get();

    $feedbackNotifications = $supplier->notifications()
      ->where('type', SupplierNotification::TYPE_FEEDBACK_REQUIRED)
      ->orderBy('created_at', 'desc')
      ->get();

    $unreadCount = $unreadNotifications->count();

    return view('suppliers.notifications.index', compact(
      'supplier',
      'notifications',
      'unreadNotifications',
      'contractNotifications',
      'performanceNotifications',
      'feedbackNotifications',
      'unreadCount'
    ));
  }

  public function markAsRead(SupplierNotification $notification)
  {
    $notification->markAsRead();
    return response()->json(['success' => true]);
  }

  public function markAllAsRead(Supplier $supplier)
  {
    $supplier->markAllNotificationsAsRead();
    return response()->json(['success' => true]);
  }

  public function getUnreadCount(Supplier $supplier)
  {
    $count = $supplier->notifications()
      ->whereNull('read_at')
      ->count();

    return response()->json(['count' => $count]);
  }

  public function getLatest(Supplier $supplier)
  {
    $notifications = $supplier->notifications()
      ->whereNull('read_at')
      ->orderBy('priority', 'desc')
      ->orderBy('created_at', 'desc')
      ->take(5)
      ->get();

    return response()->json([
      'notifications' => $notifications,
      'count' => $notifications->count()
    ]);
  }
}
