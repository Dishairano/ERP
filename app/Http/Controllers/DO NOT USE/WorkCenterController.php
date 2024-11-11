<?php

namespace App\Http\Controllers;

use App\Models\WorkCenter;
use App\Models\MaintenanceRecord;
use App\Models\EfficiencyRecord;
use Illuminate\Http\Request;

class WorkCenterController extends Controller
{
  public function index()
  {
    $workCenters = WorkCenter::paginate(10);
    return view('work-centers.index', compact('workCenters'));
  }

  public function capacity()
  {
    $workCenters = WorkCenter::with('capacityPlans')->paginate(10);
    return view('work-centers.capacity', compact('workCenters'));
  }

  public function maintenance()
  {
    $workCenters = WorkCenter::with('maintenanceRecords')->paginate(10);
    $maintenanceRecords = MaintenanceRecord::orderBy('scheduled_date')->paginate(10);
    return view('work-centers.maintenance', compact('workCenters', 'maintenanceRecords'));
  }

  public function efficiency()
  {
    $workCenters = WorkCenter::with('efficiencyRecords')->paginate(10);
    $efficiencyRecords = EfficiencyRecord::orderBy('date', 'desc')->paginate(10);
    return view('work-centers.efficiency', compact('workCenters', 'efficiencyRecords'));
  }
}
