<?php

namespace App\Http\Controllers;

use App\Models\CoreFinanceCustomerModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoreFinanceCustomerController extends Controller
{
  /**
   * Display a listing of customers.
   */
  public function index(Request $request)
  {
    $query = CoreFinanceCustomerModal::query()
      ->withCount(['receivables', 'payments']);

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Filter by country
    if ($request->has('country')) {
      $query->where('country', $request->country);
    }

    // Filter by overdue receivables
    if ($request->boolean('has_overdue')) {
      $query->withOverdueReceivables();
    }

    // Filter by credit limit exceeded
    if ($request->boolean('exceeding_credit_limit')) {
      $query->exceedingCreditLimit();
    }

    $customers = $query->orderBy('name')
      ->paginate(10);

    return view('core.finance.customers.index', compact('customers'));
  }

  /**
   * Show the form for creating a new customer.
   */
  public function create()
  {
    return view('core.finance.customers.create');
  }

  /**
   * Store a newly created customer.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:50|unique:finance_customers,code',
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

    $customer = CoreFinanceCustomerModal::create($validated);

    return redirect()
      ->route('finance.customers.show', $customer)
      ->with('success', 'Customer created successfully');
  }

  /**
   * Display the specified customer.
   */
  public function show(CoreFinanceCustomerModal $customer)
  {
    $customer->load(['creator', 'receivables' => function ($query) {
      $query->latest()->limit(5);
    }, 'payments' => function ($query) {
      $query->latest()->limit(5);
    }]);

    return view('core.finance.customers.show', compact('customer'));
  }

  /**
   * Show the form for editing the specified customer.
   */
  public function edit(CoreFinanceCustomerModal $customer)
  {
    return view('core.finance.customers.edit', compact('customer'));
  }

  /**
   * Update the specified customer.
   */
  public function update(Request $request, CoreFinanceCustomerModal $customer)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:50|unique:finance_customers,code,' . $customer->id,
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

    $customer->update($validated);

    return redirect()
      ->route('finance.customers.show', $customer)
      ->with('success', 'Customer updated successfully');
  }

  /**
   * Remove the specified customer.
   */
  public function destroy(CoreFinanceCustomerModal $customer)
  {
    // Check if customer has any receivables or payments
    if ($customer->receivables()->exists() || $customer->payments()->exists()) {
      return back()->withErrors(['error' => 'Cannot delete customer with associated receivables or payments.']);
    }

    $customer->delete();

    return redirect()
      ->route('finance.customers.index')
      ->with('success', 'Customer deleted successfully');
  }

  /**
   * Display the customer statement.
   */
  public function statement(CoreFinanceCustomerModal $customer, Request $request)
  {
    $startDate = $request->get('start_date', now()->startOfMonth());
    $endDate = $request->get('end_date', now()->endOfMonth());

    $receivables = $customer->receivables()
      ->with(['payments', 'creator'])
      ->whereBetween('date', [$startDate, $endDate])
      ->orderBy('date')
      ->get();

    return view('core.finance.customers.statement', compact('customer', 'receivables', 'startDate', 'endDate'));
  }

  /**
   * Display the customer aging report.
   */
  public function aging()
  {
    $customers = CoreFinanceCustomerModal::withCount(['receivables'])
      ->with(['receivables' => function ($query) {
        $query->where('remaining_amount', '>', 0)
          ->select('customer_id', 'due_date', 'remaining_amount');
      }])
      ->whereHas('receivables', function ($query) {
        $query->where('remaining_amount', '>', 0);
      })
      ->get()
      ->map(function ($customer) {
        $aging = [
          'current' => 0,
          '1_30' => 0,
          '31_60' => 0,
          '61_90' => 0,
          'over_90' => 0
        ];

        foreach ($customer->receivables as $receivable) {
          $daysOverdue = max(0, now()->startOfDay()->diffInDays($receivable->due_date));

          if ($daysOverdue <= 0) {
            $aging['current'] += $receivable->remaining_amount;
          } elseif ($daysOverdue <= 30) {
            $aging['1_30'] += $receivable->remaining_amount;
          } elseif ($daysOverdue <= 60) {
            $aging['31_60'] += $receivable->remaining_amount;
          } elseif ($daysOverdue <= 90) {
            $aging['61_90'] += $receivable->remaining_amount;
          } else {
            $aging['over_90'] += $receivable->remaining_amount;
          }
        }

        $customer->aging = $aging;
        return $customer;
      });

    return view('core.finance.customers.aging', compact('customers'));
  }
}
