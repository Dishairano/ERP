<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // <-- Make sure this is correctly imported

class InvoiceController extends Controller
{
  // Display the list of invoices
  public function index()
  {
    $invoices = Invoice::all();
    return view('bookkeeping-invoicing-payments', compact('invoices'));
  }

  // Show form to create a new invoice
  public function create()
  {
    return view('invoices.create');
  }

  // Store a new invoice in the database
  public function store(Request $request)
  {
    // Validate the form data
    $request->validate([
      'invoice_number' => 'required|unique:invoices',
      'client_name' => 'required',
      'amount' => 'required|numeric',
      'status' => 'required',
    ]);

    // Create the new invoice
    Invoice::create([
      'invoice_number' => $request->invoice_number,
      'client_name' => $request->client_name,
      'amount' => $request->amount,
      'status' => $request->status,
    ]);

    // Redirect back to the invoices list with a success message
    return redirect()->route('bookkeeping-invoicing-payments')->with('success', 'Invoice added successfully!');
  }
  public function downloadInvoicePdf($id)
  {
    // Find the invoice by ID
    $invoice = Invoice::findOrFail($id);

    // Load the view for the invoice PDF
    $pdf = PDF::loadView('invoices.pdf', compact('invoice'));

    // Download the PDF
    return $pdf->download('invoice_' . $invoice->invoice_number . '.pdf');
  }

  // Function to display the review page
  public function reviewInvoice($id)
  {
    $invoice = Invoice::findOrFail($id);
    return view('invoices.review', compact('invoice'));
  }
}
