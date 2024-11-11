<?php

namespace App\Http\Controllers;

use App\Models\CoreLeaveRequestModal;
use App\Models\CoreLeaveTypeModal;
use App\Models\CoreLeavePolicyModal;
use App\Models\CoreLeaveBalanceModal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoreLeaveManagementController extends Controller
{
  public function requests()
  {
    /** @var User $user */
    $user = Auth::user();

    $requests = CoreLeaveRequestModal::with(['user', 'leaveType'])
      ->when(!$user->hasRole('admin'), function ($query) use ($user) {
        return $query->where('user_id', $user->id);
      })
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('content.leave-management.requests.index', compact('requests'));
  }

  public function createRequest()
  {
    $leaveTypes = CoreLeaveTypeModal::all();
    /** @var User $user */
    $user = Auth::user();

    $balances = $leaveTypes->mapWithKeys(function ($type) use ($user) {
      return [$type->id => $user->getLeaveBalanceForType($type->id)];
    });

    return view('content.leave-management.requests.create', compact('leaveTypes', 'balances'));
  }

  // ... rest of the controller methods remain the same ...
}
