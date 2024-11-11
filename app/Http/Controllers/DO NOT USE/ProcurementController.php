<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\SupplierContract;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
  /**
   * Display procurement dashboard.
   *
   * @return \Illuminate\View\View
   */
  public function dashboard()
  {
    $totalOrders = PurchaseOrder::count();
    $pendingOrders = PurchaseOrder::where('status', 'pending')->count();
    $totalRequisitions = PurchaseRequisition::count();
    $activeContracts = SupplierContract::where('status', 'active')->count();

    return view('procurement.dashboard', compact(
      'totalOrders',
      'pendingOrders',
      'totalRequisitions',
      'activeContracts'
    ));
  }

  /**
   * Display purchase orders.
   *
   * @return \Illuminate\View\View
   */
  public function purchaseOrders()
  {
    $orders = PurchaseOrder::with(['supplier', 'items'])
      ->latest()
      ->paginate(10);

    return view('procurement.purchase-orders', compact('orders'));
  }

  /**
   * Display requisitions.
   *
   * @return \Illuminate\View\View
   */
  public function requisitions()
  {
    $requisitions = PurchaseRequisition::with(['requester', 'department'])
      ->latest()
      ->paginate(10);

    return view('procurement.requisitions', compact('requisitions'));
  }

  /**
   * Display contracts.
   *
   * @return \Illuminate\View\View
   */
  public function contracts()
  {
    $contracts = SupplierContract::with(['supplier'])
      ->latest()
      ->paginate(10);

    return view('procurement.contracts', compact('contracts'));
  }

  /**
   * Store a new purchase order.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storePurchaseOrder(Request $request)
  {
    $validated = $request->validate([
      'supplier_id' => 'required|exists:suppliers,id',
      'order_date' => 'required|date',
      'delivery_date' => 'required|date|after:order_date',
      'items' => 'required|array',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.quantity' => 'required|numeric|min:1',
      'items.*.unit_price' => 'required|numeric|min:0',
      'notes' => 'nullable|string'
    ]);

    PurchaseOrder::create([
      ...$validated,
      'status' => 'draft',
      'created_by' => auth()->id()
    ]);

    return redirect()->route('procurement.purchase-orders')
      ->with('success', 'Purchase order created successfully.');
  }

  /**
   * Store a new requisition.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeRequisition(Request $request)
  {
    $validated = $request->validate([
      'department_id' => 'required|exists:departments,id',
      'required_date' => 'required|date|after:today',
      'items' => 'required|array',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.quantity' => 'required|numeric|min:1',
      'priority' => 'required|in:low,medium,high',
      'reason' => 'required|string',
      'notes' => 'nullable|string'
    ]);

    PurchaseRequisition::create([
      ...$validated,
      'status' => 'pending',
      'requester_id' => auth()->id()
    ]);

    return redirect()->route('procurement.requisitions')
      ->with('success', 'Purchase requisition created successfully.');
  }

  /**
   * Store a new contract.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeContract(Request $request)
  {
    $validated = $request->validate([
      'supplier_id' => 'required|exists:suppliers,id',
      'type' => 'required|string',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'terms' => 'required|array',
      'value' => 'required|numeric|min:0',
      'payment_terms' => 'required|string',
      'notes' => 'nullable|string'
    ]);

    SupplierContract::create([
      ...$validated,
      'status' => 'draft',
      'created_by' => auth()->id()
    ]);

    return redirect()->route('procurement.contracts')
      ->with('success', 'Supplier contract created successfully.');
  }
}
