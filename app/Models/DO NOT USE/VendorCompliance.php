<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorCompliance extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'supplier_id',
    'compliance_requirements',
    'certifications',
    'last_assessment_date',
    'next_assessment_date',
    'risk_level',
    'notes'
  ];

  protected $casts = [
    'compliance_requirements' => 'json',
    'certifications' => 'json',
    'last_assessment_date' => 'date',
    'next_assessment_date' => 'date'
  ];

  public function supplier()
  {
    return $this->belongsTo(Supplier::class);
  }

  public function isHighRisk()
  {
    return $this->risk_level === 'High';
  }

  public function needsAssessment()
  {
    return now()->greaterThanOrEqual($this->next_assessment_date);
  }

  public function hasValidCertifications()
  {
    $certifications = collect($this->certifications);
    return $certifications->every(function ($cert) {
      return isset($cert['valid_until']) &&
        now()->lessThan($cert['valid_until']);
    });
  }

  public function getExpiredCertifications()
  {
    return collect($this->certifications)->filter(function ($cert) {
      return isset($cert['valid_until']) &&
        now()->greaterThan($cert['valid_until']);
    });
  }
}
