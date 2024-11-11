<?php

namespace App\Exports;

use App\Models\SecurityAuditLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SecurityAuditLogExport implements FromCollection, WithHeadings, WithMapping
{
  public function collection()
  {
    return SecurityAuditLog::with('user')->orderBy('created_at', 'desc')->get();
  }

  public function headings(): array
  {
    return [
      'Timestamp',
      'User',
      'Action',
      'Details',
      'IP Address',
      'Status',
      'User Agent',
    ];
  }

  public function map($log): array
  {
    return [
      $log->created_at,
      $log->user->name,
      $log->action,
      $log->details,
      $log->ip_address,
      $log->status,
      $log->user_agent,
    ];
  }
}
