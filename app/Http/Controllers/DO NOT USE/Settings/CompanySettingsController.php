<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanySettingsController extends Controller
{
  public function index()
  {
    $company = [
      'name' => config('app.company_name'),
      'address' => config('app.company_address'),
      'phone' => config('app.company_phone'),
      'email' => config('app.company_email'),
      'tax_number' => config('app.company_tax'),
      'logo' => config('app.company_logo'),
    ];

    return view('settings.company.index', compact('company'));
  }

  public function update(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'address' => 'required|string|max:500',
      'phone' => 'required|string|max:20',
      'email' => 'required|email|max:255',
      'tax_number' => 'nullable|string|max:50',
      'logo' => 'nullable|image|max:2048',
    ]);

    // Handle logo upload if provided
    if ($request->hasFile('logo')) {
      $path = $request->file('logo')->store('company', 'public');
      $validated['logo'] = $path;
    }

    // Update company settings in database or config
    // This is a placeholder - implement actual storage method

    return redirect()->route('settings.company')
      ->with('success', 'Company profile updated successfully');
  }
}
