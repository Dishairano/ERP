<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceVendorModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoreFinanceVendorController extends Controller
{
  /**
   * Display a listing of vendors.
   */
  public function index(Request $request)
  {
    $query = CoreFinanceVendorModal::query()
      ->withCount(['payables', 'payments']);

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter by country
    if ($request->has('country')) {
      $query->where('country', $request->country);
    }

    // Filter by overdue payables
    if ($request->boolean('has_overdue')) {
      $query->withOverduePayables();
    }

    // Filter by credit limit exceeded
    if ($request->boolean('exceeding_credit_limit')) {
      $query->exceedingCreditLimit();
    }

    $vendors = $query->orderBy('name')
      ->paginate(10);

    return view('core.finance.vendors.index', compact('vendors'));
  }

  /**
   * Show the form for creating a new vendor.
   */
  public function create()
  {
    return view('core.finance.vendors.create');
  }

  /**
   * Store a newly created vendor.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:50|unique:finance_vendors,code',
      'name' => 'required|string|max:255',
      'contact_person' => 'nullable|string|max:255',
      'email' => 'nullable|email|max:255',
      'phone' => 'nullable|string|max:50',
      'mobile' => 'nullable|string|max:50',
      'website' => 'nullable|url|max:255',
      'tax_number' => 'nullable|string|max:50',
      'registration_number' => 'nullable|string|max:50',
      'address_line1' => 'nullable|string|max:255',
      'address_line2' => 'nullable|string|max:255',
      'city' => 'nullable|string|max:100',
      'state' => 'nullable|string|max:100',
      'postal_code' => 'nullable|string|max:20',
      'country' => 'nullable|string|max:100',
      'currency' => 'required|string|size:3',
      'payment_terms' => 'nullable|string|max:255',
      'credit_limit' => 'required|numeric|min:0',
      'status' => 'required|string|in:active,inactive',
      'notes' => 'nullable|string'
    ]);

    $validated['created_by'] = Auth::id();

    $vendor = CoreFinanceVendorModal::create($validated);

    return redirect()
      ->route('finance.vendors.show', $vendor)
      ->with('success', 'Vendor created successfully');
  }

  /**
   * Display the specified vendor.
   */
  public function show(CoreFinanceVendorModal $vendor)
  {
    $vendor->load(['creator', 'payables' => function ($query) {
      $query->latest()->limit(5);
    }, 'payments' => function ($query) {
      $query->latest()->limit(5);
    }]);

    return view('core.finance.vendors.show', compact('vendor'));
  }

  /**
   * Show the form for editing the specified vendor.
   */
  public function edit(CoreFinanceVendorModal $vendor)
  {
    return view('core.finance.vendors.edit', compact('vendor'));
  }

  /**
   * Update the specified vendor.
   */
  public function update(Request $request, CoreFinanceVendorModal $vendor)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:50|unique:finance_vendors,code,' . $vendor->id,
      'name' => 'required|string|max:255',
      'contact_person' => 'nullable|string|max:255',
      'email' => 'nullable|email|max:255',
      'phone' => 'nullable|string|max:50',
      'mobile' => 'nullable|string|max:50',
      'website' => 'nullable|url|max:255',
      'tax_number' => 'nullable|string|max:50',
      'registration_number' => 'nullable|string|max:50',
      'address_line1' => 'nullable|string|max:255',
      'address_line2' => 'nullable|string|max:255',
      'city' => 'nullable|string|max:100',
      'state' => 'nullable|string|max:100',
      'postal_code' => 'nullable|string|max:20',
      'country' => 'nullable|string|max:100',
      'currency' => 'required|string|size:3',
      'payment_terms' => 'nullable|string|max:255',
      'credit_limit' => 'required|numeric|min:0',
      'status' => 'required|string|in:active,inactive',
      'notes' => 'nullable|string'
    ]);

    $vendor->update($validated);

    return redirect()
      ->route('finance.vendors.show', $vendor)
      ->with('success', 'Vendor updated successfully');
  }

  /**
   * Remove the specified vendor.
   */
  public function destroy(CoreFinanceVendorModal $vendor)
  {
    // Check if vendor has any payables or payments
    if ($vendor->payables()->exists() || $vendor->payments()->exists()) {
      return back()->withErrors(['error' => 'Cannot delete vendor with associated payables or payments.']);
    }

    $vendor->delete();

    return redirect()
      ->route('finance.vendors.index')
      ->with('success', 'Vendor deleted successfully');
  }

  /**
   * Display the vendor statement.
   */
  public function statement(CoreFinanceVendorModal $vendor, Request $request)
  {
    $startDate = $request->get('start_date', now()->startOfMonth());
    $endDate = $request->get('end_date', now()->endOfMonth());

    $payables = $vendor->payables()
      ->with(['payments', 'creator'])
      ->whereBetween('date', [$startDate, $endDate])
      ->orderBy('date')
      ->get();

    return view('core.finance.vendors.statement', compact('vendor', 'payables', 'startDate', 'endDate'));
  }

  /**
   * Display the vendor aging report.
   */
  public function aging()
  {
    $vendors = CoreFinanceVendorModal::withCount(['payables'])
      ->with(['payables' => function ($query) {
        $query->where('remaining_amount', '>', 0)
          ->select('vendor_id', 'due_date', 'remaining_amount');
      }])
      ->whereHas('payables', function ($query) {
        $query->where('remaining_amount', '>', 0);
      })
      ->get()
      ->map(function ($vendor) {
        $aging = [
          'current' => 0,
          '1_30' => 0,
          '31_60' => 0,
          '61_90' => 0,
          'over_90' => 0
        ];

        foreach ($vendor->payables as $payable) {
          $daysOverdue = max(0, now()->startOfDay()->diffInDays($payable->due_date));

          if ($daysOverdue <= 0) {
            $aging['current'] += $payable->remaining_amount;
          } elseif ($daysOverdue <= 30) {
            $aging['1_30'] += $payable->remaining_amount;
          } elseif ($daysOverdue <= 60) {
            $aging['31_60'] += $payable->remaining_amount;
          } elseif ($daysOverdue <= 90) {
            $aging['61_90'] += $payable->remaining_amount;
          } else {
            $aging['over_90'] += $payable->remaining_amount;
          }
        }

        $vendor->aging = $aging;
        return $vendor;
      });

    return view('core.finance.vendors.aging', compact('vendors'));
  }
}
