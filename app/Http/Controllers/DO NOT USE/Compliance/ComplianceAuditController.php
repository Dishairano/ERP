<?php

namespace App\Http\Controllers\Compliance;

use App\Http\Controllers\Controller;
use App\Models\ComplianceAudit;
use Illuminate\Http\Request;

class ComplianceAuditController extends Controller
{
  public function index()
  {
    $audits = ComplianceAudit::latest()->paginate(10);
    return view('compliance.audits.index', compact('audits'));
  }

  public function create()
  {
    return view('compliance.audits.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'audit_type' => 'required|string',
      'status' => 'required|string',
      'scheduled_date' => 'required|date',
      'completion_date' => 'nullable|date',
      'findings' => 'nullable|string',
      'recommendations' => 'nullable|string',
      'auditor_name' => 'required|string',
      'department' => 'required|string',
      'scope' => 'required|string',
      'action_items' => 'nullable|string',
      'follow_up_date' => 'nullable|date'
    ]);

    ComplianceAudit::create($validated);

    return redirect()->route('compliance.audits.index')
      ->with('success', 'Compliance audit created successfully.');
  }

  public function show(ComplianceAudit $audit)
  {
    return view('compliance.audits.show', compact('audit'));
  }

  public function edit(ComplianceAudit $audit)
  {
    return view('compliance.audits.edit', compact('audit'));
  }

  public function update(Request $request, ComplianceAudit $audit)
  {
    $validated = $request->validate([
      'audit_type' => 'required|string',
      'status' => 'required|string',
      'scheduled_date' => 'required|date',
      'completion_date' => 'nullable|date',
      'findings' => 'nullable|string',
      'recommendations' => 'nullable|string',
      'auditor_name' => 'required|string',
      'department' => 'required|string',
      'scope' => 'required|string',
      'action_items' => 'nullable|string',
      'follow_up_date' => 'nullable|date'
    ]);

    $audit->update($validated);

    return redirect()->route('compliance.audits.index')
      ->with('success', 'Compliance audit updated successfully.');
  }

  public function destroy(ComplianceAudit $audit)
  {
    $audit->delete();

    return redirect()->route('compliance.audits.index')
      ->with('success', 'Compliance audit deleted successfully.');
  }
}
