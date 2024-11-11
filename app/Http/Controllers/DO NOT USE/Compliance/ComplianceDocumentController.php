<?php

namespace App\Http\Controllers\Compliance;

use App\Http\Controllers\Controller;
use App\Models\ComplianceDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComplianceDocumentController extends Controller
{
  public function index()
  {
    $documents = ComplianceDocument::latest()->paginate(10);
    return view('compliance.documents.index', compact('documents'));
  }

  public function create()
  {
    return view('compliance.documents.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'document_type' => 'required|string',
      'file' => 'required|file|max:10240',
      'expiry_date' => 'nullable|date',
      'status' => 'required|string',
      'description' => 'nullable|string',
      'department' => 'required|string',
      'owner' => 'required|string',
      'tags' => 'nullable|string'
    ]);

    $path = $request->file('file')->store('compliance-documents');
    $validated['file_path'] = $path;

    ComplianceDocument::create($validated);

    return redirect()->route('compliance.documents.index')
      ->with('success', 'Compliance document uploaded successfully.');
  }

  public function show(ComplianceDocument $document)
  {
    return view('compliance.documents.show', compact('document'));
  }

  public function edit(ComplianceDocument $document)
  {
    return view('compliance.documents.edit', compact('document'));
  }

  public function update(Request $request, ComplianceDocument $document)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'document_type' => 'required|string',
      'file' => 'nullable|file|max:10240',
      'expiry_date' => 'nullable|date',
      'status' => 'required|string',
      'description' => 'nullable|string',
      'department' => 'required|string',
      'owner' => 'required|string',
      'tags' => 'nullable|string'
    ]);

    if ($request->hasFile('file')) {
      Storage::delete($document->file_path);
      $path = $request->file('file')->store('compliance-documents');
      $validated['file_path'] = $path;
    }

    $document->update($validated);

    return redirect()->route('compliance.documents.index')
      ->with('success', 'Compliance document updated successfully.');
  }

  public function destroy(ComplianceDocument $document)
  {
    Storage::delete($document->file_path);
    $document->delete();

    return redirect()->route('compliance.documents.index')
      ->with('success', 'Compliance document deleted successfully.');
  }

  public function download(ComplianceDocument $document)
  {
    return Storage::download($document->file_path, $document->title);
  }
}
