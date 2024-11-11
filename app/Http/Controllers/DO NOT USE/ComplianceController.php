<?php

namespace App\Http\Controllers;

use App\Models\ComplianceRequirement;
use App\Models\ComplianceAudit;
use App\Models\ComplianceDocument;
use App\Models\ComplianceTraining;
use App\Models\ComplianceNotification;
use Illuminate\Http\Request;

class ComplianceController extends Controller
{
  public function requirements()
  {
    $requirements = ComplianceRequirement::with(['audits', 'documents'])->get();
    return view('compliance.requirements', compact('requirements'));
  }

  public function audits()
  {
    $audits = ComplianceAudit::with(['requirements', 'documents'])->get();
    return view('compliance.audits', compact('audits'));
  }

  public function documents()
  {
    $documents = ComplianceDocument::with(['requirements', 'audits'])->get();
    return view('compliance.documents', compact('documents'));
  }

  public function trainings()
  {
    $trainings = ComplianceTraining::with(['requirements', 'completions'])->get();
    return view('compliance.trainings', compact('trainings'));
  }

  public function notifications()
  {
    $notifications = ComplianceNotification::with(['requirement', 'user'])->get();
    return view('compliance.notifications', compact('notifications'));
  }
}
