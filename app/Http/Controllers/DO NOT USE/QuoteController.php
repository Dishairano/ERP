<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Customer;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with('customer')->get();
        return view('quotes.index', compact('quotes'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('quotes.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'quote_date' => 'required|date',
            'total_amount' => 'required|numeric',
        ]);

        Quote::create($request->all());

        return redirect()->route('quotes.index')->with('success', 'Quote created successfully.');
    }
}