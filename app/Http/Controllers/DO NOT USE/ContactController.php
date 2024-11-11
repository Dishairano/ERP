<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\Lead;
use Illuminate\Http\Request;

class ContactController extends Controller
{
  /**
   * Display the contacts dashboard.
   *
   * @return \Illuminate\View\View
   */
  public function dashboard()
  {
    $totalCustomers = Customer::count();
    $totalLeads = Lead::count();
    $newLeadsThisMonth = Lead::whereMonth('created_at', now()->month)->count();
    $recentContacts = Contact::with(['contactable'])
      ->latest()
      ->take(5)
      ->get();

    return view('contacts.dashboard', compact(
      'totalCustomers',
      'totalLeads',
      'newLeadsThisMonth',
      'recentContacts'
    ));
  }

  /**
   * Display a listing of customers.
   *
   * @return \Illuminate\View\View
   */
  public function customers()
  {
    $customers = Customer::with(['contacts'])
      ->latest()
      ->paginate(10);

    return view('contacts.customers', compact('customers'));
  }

  /**
   * Display a listing of leads.
   *
   * @return \Illuminate\View\View
   */
  public function leads()
  {
    $leads = Lead::with(['contacts'])
      ->latest()
      ->paginate(10);

    return view('contacts.leads', compact('leads'));
  }

  /**
   * Display a listing of prospects.
   *
   * @return \Illuminate\View\View
   */
  public function prospects()
  {
    $prospects = Lead::where('status', 'prospect')
      ->with(['contacts'])
      ->latest()
      ->paginate(10);

    return view('contacts.prospects', compact('prospects'));
  }

  /**
   * Store a new contact.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'email' => 'required|email|unique:contacts,email',
      'phone' => 'nullable|string|max:20',
      'company' => 'nullable|string|max:255',
      'position' => 'nullable|string|max:255',
      'type' => 'required|in:customer,lead,prospect',
      'status' => 'required|string|max:255',
      'source' => 'nullable|string|max:255',
      'notes' => 'nullable|string'
    ]);

    Contact::create($validated);

    return redirect()->back()
      ->with('success', 'Contact created successfully.');
  }

  /**
   * Update the specified contact.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Contact  $contact
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, Contact $contact)
  {
    $validated = $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'email' => 'required|email|unique:contacts,email,' . $contact->id,
      'phone' => 'nullable|string|max:20',
      'company' => 'nullable|string|max:255',
      'position' => 'nullable|string|max:255',
      'type' => 'required|in:customer,lead,prospect',
      'status' => 'required|string|max:255',
      'source' => 'nullable|string|max:255',
      'notes' => 'nullable|string'
    ]);

    $contact->update($validated);

    return redirect()->back()
      ->with('success', 'Contact updated successfully.');
  }

  /**
   * Remove the specified contact.
   *
   * @param  \App\Models\Contact  $contact
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(Contact $contact)
  {
    $contact->delete();

    return redirect()->back()
      ->with('success', 'Contact deleted successfully.');
  }
}
