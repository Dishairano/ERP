<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AuditTrailExport implements FromCollection, WithHeadings
{
  public function collection()
  {
    return DB::table('audit_logs')
      ->select('user_id', 'action', 'model_type', 'model_id', 'changes', 'created_at')
      ->orderBy('created_at', 'desc')
      ->get();
  }

  public function headings(): array
  {
    return [
      'User ID',
      'Action',
      'Model Type',
      'Model ID',
      'Changes',
      'Created At'
    ];
  }
}
