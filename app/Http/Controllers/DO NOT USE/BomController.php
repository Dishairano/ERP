<?php

namespace App\Http\Controllers;

use App\Models\BillOfMaterial;
use App\Models\BomComponent;
use Illuminate\Http\Request;

class BomController extends Controller
{
  public function items()
  {
    $boms = BillOfMaterial::with('components')->paginate(10);
    return view('bom.items', compact('boms'));
  }

  public function versions()
  {
    $boms = BillOfMaterial::orderBy('version', 'desc')->paginate(10);
    return view('bom.versions', compact('boms'));
  }

  public function costing()
  {
    $boms = BillOfMaterial::with(['components' => function ($query) {
      $query->with('costs');
    }])->paginate(10);
    return view('bom.costing', compact('boms'));
  }

  public function engineering()
  {
    $boms = BillOfMaterial::with(['changes', 'approvals'])->paginate(10);
    return view('bom.engineering', compact('boms'));
  }
}
