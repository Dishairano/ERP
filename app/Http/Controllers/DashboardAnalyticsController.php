<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CoreDashboardAnalyticsModal;

class DashboardAnalyticsController extends Controller
{
  public function index()
  {
    return view('core.dashboard.analytics');
  }
}
