<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogisticsController extends Controller
{
  public function index()
  {
    return view('logistics.index');
  }

  public function inventory()
  {
    return view('logistics.inventory');
  }

  public function shipments()
  {
    return view('logistics.shipments');
  }
}
