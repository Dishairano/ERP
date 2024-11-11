<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->get();
        return view('purchase_orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('purchase_orders.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric',
        ]);

        PurchaseOrder::create($request->all());

        return redirect()->route('purchase_orders.index')->with('success', 'Purchase Order created successfully.');
    }
}
