<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
  public function index()
  {
    $recruitments = Recruitment::all();
    return view('hrm.recruitment.index', compact('recruitments'));
  }

  public function create()
  {
    return view('hrm.recruitment.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'position' => 'required|string|max:255',
      'department' => 'required|string|max:255',
      'description' => 'required|string',
      'requirements' => 'required|string',
      'status' => 'required|string|in:open,in-progress,closed',
      'deadline' => 'required|date',
      'salary_range' => 'required|string',
      'location' => 'required|string|max:255',
      'employment_type' => 'required|string|in:full-time,part-time,contract,temporary'
    ]);

    Recruitment::create($validated);

    return redirect()->route('hrm.recruitment')->with('success', 'Job posting created successfully');
  }

  public function show($id)
  {
    $recruitment = Recruitment::findOrFail($id);
    return view('hrm.recruitment.show', compact('recruitment'));
  }

  public function edit($id)
  {
    $recruitment = Recruitment::findOrFail($id);
    return view('hrm.recruitment.edit', compact('recruitment'));
  }

  public function update(Request $request, $id)
  {
    $recruitment = Recruitment::findOrFail($id);

    $validated = $request->validate([
      'position' => 'required|string|max:255',
      'department' => 'required|string|max:255',
      'description' => 'required|string',
      'requirements' => 'required|string',
      'status' => 'required|string|in:open,in-progress,closed',
      'deadline' => 'required|date',
      'salary_range' => 'required|string',
      'location' => 'required|string|max:255',
      'employment_type' => 'required|string|in:full-time,part-time,contract,temporary'
    ]);

    $recruitment->update($validated);

    return redirect()->route('hrm.recruitment')->with('success', 'Job posting updated successfully');
  }

  public function destroy($id)
  {
    $recruitment = Recruitment::findOrFail($id);
    $recruitment->delete();

    return redirect()->route('hrm.recruitment')->with('success', 'Job posting deleted successfully');
  }
}
