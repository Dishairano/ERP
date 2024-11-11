<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    // Display all reports
    public function index()
    {
        $reports = Report::all();
        return view('reports.index', compact('reports'));
    }

    // Show form to create a new report
    public function create()
    {
        return view('reports.create');
    }

    // Store the report in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_income' => 'nullable|numeric|min:0',
            'total_expense' => 'nullable|numeric|min:0',
            'assets' => 'nullable|numeric|min:0',
            'liabilities' => 'nullable|numeric|min:0',
        ]);

        $validated['net_income'] = $validated['total_income'] - $validated['total_expense'];
        $validated['equity'] = $validated['assets'] - $validated['liabilities'];

        Report::create($validated);

        return redirect()->route('reports.index')->with('success', 'Report generated successfully!');
    }

    // Show a specific report
    public function show($id)
    {
        $report = Report::findOrFail($id);
        return view('reports.show', compact('report'));
    }

    // Download report as PDF
    public function downloadPDF($id)
    {
        $report = Report::findOrFail($id);
        $pdf = PDF::loadView('reports.pdf', compact('report'));
        return $pdf->download('report_' . $report->id . '.pdf');
    }
}