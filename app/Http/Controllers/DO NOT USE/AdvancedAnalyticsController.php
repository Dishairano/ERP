<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdvancedAnalyticsController extends Controller
{
  public function machineLearning()
  {
    return view('advanced-analytics.ml');
  }
}
