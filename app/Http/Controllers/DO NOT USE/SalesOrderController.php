<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\Customer;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index()
    {
        $salesOrders = SalesOrder::with('customer')->get();
        return view('sales_orders.index', compact('salesOrders'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('sales_orders.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric',
        ]);

        SalesOrder::create($request->all());

        return redirect()->route('sales_orders.index')->with('success', 'Sales Order created successfully.');
    }
}