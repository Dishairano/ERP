<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class GoodsReceiptController extends Controller
{
    public function index()
    {
        $goodsReceipts = GoodsReceipt::with('purchaseOrder')->get();
        return view('goods_receipts.index', compact('goodsReceipts'));
    }

    public function create()
    {
        $purchaseOrders = PurchaseOrder::all();
        return view('goods_receipts.create', compact('purchaseOrders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required',
            'receipt_date' => 'required|date',
            'quantity_received' => 'required|integer',
        ]);

        GoodsReceipt::create($request->all());

        return redirect()->route('goods_receipts.index')->with('success', 'Goods Receipt created successfully.');
    }
}