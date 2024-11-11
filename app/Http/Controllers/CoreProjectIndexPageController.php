<?php

namespace App\Http\Controllers;

use App\Models\CoreProjectModal;
use Illuminate\Http\Request;

class CoreProjectIndexPageController extends Controller
{
  public function index()
  {
    $projects = CoreProjectModal::with('manager')
      ->latest()
      ->paginate(10);

    return view('content.projects.index', compact('projects'));
  }
}
